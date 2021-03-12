<?php

namespace SquadMS\Servers\Services;

use SquadMS\Servers\Data\QueryResult;
use SquadMS\Servers\Events\Internal\User\PlayedOnServer;
use SquadMS\Servers\Events\Internal\User\PlayingOnServer;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Jobs\NotifySeeding;
use SquadMS\Servers\Models\PlayerServerInfo;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use skyraptor\LaravelSteamLogin\SteamUser;
use SquadMS\Foundation\Helpers\UserHelper;
use SquadMS\Foundation\Interfaces\SquadMSUserInterface;
use SquadMS\Foundation\Jobs\QueryUsers;
use SquadMS\Foundation\Services\UserService;

class ServerQueryService {
    /**
     * Processes the query result of a server.
     *
     * @param QueryResult $result
     * @return void
     */
    public static function processResult(QueryResult $result) : void
    {
        if ($result->online()) {
            if ($result->server()->announce_seeding) {
                self::announceSeeding($result->count(), $result->server());
            }

            /* Get the Users, create those that do not exist */
            $users = self::getUsersBySteamIds($result->steamIds());

            self::addPlaytime($users, $result->server(), self::isSeeding($result->count()));

            self::queryMissingPlayers($users);
        } else {
            /* Save playtimes to database */
            $result->server()->savePlaytimes();
        }

        $result->createFrontendCache();
    }

    public static function announceSeeding(int $count, Server $server) : void
    {
        /* Get empty since timestamp (if there is one) */
        $emptySince = $server->empty_since;

        if (!$count && !$emptySince) {
            $server->empty_since = Carbon::now();
        }

        /* Compute a modifier to randomize the min player amount for announcing */
        $mod = rand(0, 3);

        /* Count the players on the Server */
        /* Treshold, do nothing to to prevent solo farming */
        if ($count > config('sqms.seeding.max')) { // More than max players to announce seeding
            /* Delete the server empty timestamp */
            $server->forgetEmptySince();

            /* Notify Discord: Seeding ended */
            //TODO
        } else if ($count >= config('sqms.seeding.min') + $mod) { // Min players to announce seeding (with variation)
            /* Delete the server empty timestamp */
            $server->forgetEmptySince();

            /* Check if empty since timestamp was older than 15 Minutes */
            if ($emptySince && Carbon::now()->greaterThanOrEqualTo($emptySince->addMinutes(config('sqms.seeding.announce')))) {
                /* Notify Discord: Seeding started */
                NotifySeeding::dispatch($count);
            }
        }
    }

    public static function getUsersBySteamIds(array $steamIds) : Collection
    {
        if (count($steamIds)) {
            $steamUsers = [];
            foreach ($steamIds as $steamId) {
                $steamUsers[] = new SteamUser($steamId);
            }
    
            /* Bulk create the users shallowly and check if anything has been created or updated */
            if (UserService::createOrUpdateBulk($steamUsers, true)) {
                /* Query and get the created users */
                return UserHelper::getUserModel()->query()->whereIn('steam_id_64', $steamIds)->get();
            }
        }
        
        return new Collection();
    }

    public static function addPlaytime(Collection $users, Server $server, bool $grantSeedingPoints = false) : void
    {
        /* Get now for consistency */
        $now = Carbon::now();

        /* Try to get playtimes from cache, will get an empty array if no one is online or no cache exists */
        $playtimes = self::getServerPlaytimesCache($server);
        
        /* Do not do unecessary calculations or queries when no one was online before */
        if (count($playtimes)) {
            /* Get the users that have left the server */
            $leftUsers = UserHelper::getUserModel()->query()->whereIn('steam_id_64', collect(array_keys($playtimes))->diff($users->pluck('steam_id_64')->toArray())->toArray())->get();

            /** @var \App\User Add playtimes for left users */
            foreach ($leftUsers as $user) {
                /* Clear the cache for the is_playing attribute */
                $user->clearIsPlayingCache();

                /* Clear the last playing cache to be sure */
                $user->clearLastPlayingCache();

                /* Get the timestamp the user joined on */
                $joined = Carbon::parse(Arr::get($playtimes, $user->steam_id_64 . '.joined'));

                /* Determine how long the player was playing */
                $playtime = $joined->diffInMinutes($now);

                /* Add the computed playtime */
                $user->addPlaytime($server, $playtime);

                /* Add the cached seeding points from the seeding seconds */
                if (($seedingPoints = floor(Arr::get($playtimes, $user->steam_id_64 . '.seedingSeconds', 0) / 60))) {
                    $user->addSeedingPoints($seedingPoints);
                }         

                /* Trigger the PlayedOnServer event */
                event(new PlayedOnServer($user, $server, $joined, Arr::get($playtimes, $user->steam_id_64 . '.playtime', 0), Arr::get($playtimes, $user->steam_id_64 . '.seedingPoints', 0)));

                /* Remove the user from the playtime array */
                unset($playtimes[$user->steam_id_64]);
            }
        }

        /* Check that any users are playing in order to prevent unnecessary computation */
        if ($users->count()) {
            /* Get Users that joined the server and insert a new PlayerServerInfo with the first_playing date if it does not exist or is null */
            $newUsers = $users->whereNotIn('steam_id_64', array_keys($playtimes));
            /** @var \SquadMS\Servers\Interfaces\SquadMSServersUserInterface $user */
            foreach ($newUsers as $user) {
                $user->setFirstPlaying($server, $now);
            }

            /* Update last_playing in one go, we do not have to clear user cache since user will be shown as online anyways and cleared on disconnect */
            PlayerServerInfo::where('server_id', $server->id)->whereIn('user_id', $users->pluck('id')->toArray())->update([
                'last_playing' => $now,
            ]);

            /** @var \App\User Increase playtimes in cache for online users */
            foreach ($users as $user) {
                /* Set the cache for the is_playing attribute */
                $user->setIsPlayingCache();

                /* If the server is in seeding mode, set or increase seeding points */
                if ($grantSeedingPoints) {
                    /* Add seeding time if there was a timestamp */
                    if (Arr::has($playtimes, $user->steam_id_64 . '.seedingSince')) {
                        /* Check how many seconds have passed since last seeding timestamp */
                        $diff = Carbon::parse(Arr::get($playtimes, $user->steam_id_64 . '.seedingSince'))->diffInSeconds($now);

                        /* Add seconds to the seeding playtime */
                        Arr::set($playtimes, $user->steam_id_64 . '.seedingSeconds', Arr::get($playtimes, $user->steam_id_64 . '.seedingSeconds', 0) + $diff);
                    }

                    /* (Re-)Set seeding since timestamp */
                    Arr::set($playtimes, $user->steam_id_64 . '.seedingSince', $now->toDateTimeString());
                } else {
                    /* Remove the seeding since timestamp as we are no longer in seeding mode */
                    Arr::forget($playtimes, $user->steam_id_64 . '.seedingSince');
                }

                /* Set joined time to the previous time or now */
                Arr::set($playtimes, $user->steam_id_64 . '.joined', Arr::get($playtimes, $user->steam_id_64 . '.joined', $now->toDateTimeString()));

                /* Trigger the PlayingOnServer evnet */
                event(new PlayingOnServer($user, $server));
            }
        }

        /* Write the modified playtimes array back to the Cache */
        self::setServerPlaytimesCache($server, $playtimes);
    }

    public static function queryMissingPlayers(Collection $users) : void
    {
        /* Dont do anything if nothing was provided */
        if (!$users->count()) {
            return;
        }

        /* Check if we got any ids */
        QueryUsers::dispatch($users->pluck('steam_id_64')->toArray());
    }

    /**
     * Determines if the server is in seeding mode or not
     * by the given amount of online players.
     *
     * @param integer $count
     * @return boolean
     */
    public static function isSeeding(int $count = 0) : bool
    {
        return $count >= config('sqms.seeding.min') && $count <= config('sqms.seeding.max');
    }

    public static function setServerPlaytimesCache(Server $server, array $playtimes) : void
    {
        Cache::tags(['server-playtimes'])->forever('server-playtimes-' . $server->id, $playtimes);
    }

    public static function getServerPlaytimesCache(Server $server) : array
    {
        return Cache::tags(['server-playtimes'])->get('server-playtimes-' . $server->id, []);
    }

    public static function clearServerPlaytimesCache(?Server $server = null) : void
    {
        if ($server) {
            Cache::tags(['server-playtimes'])->forget('server-playtimes-' . $server->id);
        } else {
            Cache::tags(['server-playtimes'])->flush();
        }
    }
}