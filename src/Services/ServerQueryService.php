<?php

namespace SquadMS\Servers\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use SquadMS\Foundation\Auth\SteamUser;
use SquadMS\Foundation\Jobs\FetchUsers;
use SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Foundation\Repositories\UserRepository;
use SquadMS\Servers\Data\ServerQueryResult;

class ServerQueryService
{
    /**
     * Processes the query result of a server.
     *
     * @param  ServerQueryResult  $result
     * @return void
     */
    public static function processResult(ServerQueryResult $result): void
    {
        if ($result->online()) {
            /* Get the Users, create those that do not exist */
            $users = self::getUsersBySteamIds($result->steamIds());

            /* Query the users that have not been queried yet */
            self::fetchMissingPlayers($users);
        }

        /* Properly save the ServerQueryResult to Cache */
        self::createFrontendCache($result);
    }

    public static function getUsersBySteamIds(array $steamIds): Collection
    {
        if (count($steamIds)) {
            $steamUsers = [];
            foreach ($steamIds as $steamId) {
                $steamUsers[] = new SteamUser($steamId);
            }

            /* Bulk create the users shallowly and check if anything has been created or updated */
            if (UserRepository::createOrUpdateBulk($steamUsers, true)) {
                /* Query and get the created users */
                return SquadMSUser::whereIn('steam_id_64', $steamIds)->get();
            }
        }

        return new Collection();
    }

    public static function fetchMissingPlayers(Collection $users): void
    {
        /* Dont do anything if nothing was provided */
        if (! $users->count()) {
            return;
        }

        /* Check if we got any ids */
        FetchUsers::dispatch($users->pluck('steam_id_64')->toArray());
    }

    /**
     * Saves the ServerQueryResult to cache in a safe manner. That means, if
     * the result is offline it will not save it until the last online result is older
     * than the configured threshold.
     *
     * @param  ServerQueryResult  $result
     * @return void
     */
    public static function createFrontendCache(ServerQueryResult $result): void
    {
        if (! $result->online()) {
            $oldResult = $result->server()->last_query_result;

            if (
                ! $oldResult instanceof ServerQueryResult ||
                ! $oldResult->online() ||
                $oldResult->created()->greaterThan(Carbon::now()->subMinutes(5))
            ) {
                return;
            }
        }

        $result->save();
    }
}
