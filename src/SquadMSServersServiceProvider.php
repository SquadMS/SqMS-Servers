<?php

namespace SquadMS\Servers;

use Filament\Forms\Components\Select;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use SquadMS\Foundation\Contracts\SquadMSModuleServiceProvider;
use SquadMS\Foundation\Facades\SquadMSNavigation;
use SquadMS\Foundation\Facades\SquadMSPermissions;
use SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Filament\Resources\ServerResource;
use SquadMS\Servers\Http\Livewire\Server as LivewireServer;
use SquadMS\Servers\Http\Livewire\ServerEntry;
use SquadMS\Servers\Http\Middleware\WorkerAuth;
use SquadMS\Servers\Jobs\QueryServer;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Policies\ServerPolicy;

class SquadMSServersServiceProvider extends SquadMSModuleServiceProvider
{
    public static string $name = 'sqms-servers';

    protected array $resources = [
        ServerResource::class,
    ];

    protected array $livewireComponents = [
        'server-entry' => ServerEntry::class,
        'server'       => LivewireServer::class,
    ];

    public function configureModule(Package $package): void
    {
        $package->hasAssets()
                ->hasRoutes(['api', 'channels', 'web']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function registeringModule(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function bootedModule(): void
    {
        /* Permissions */
        foreach (Config::get('sqms-servers.permissions.definitions', []) as $definition => $displayName) {
            SquadMSPermissions::define(Config::get('sqms-servers.permissions.module'), $definition, $displayName);
        }

        SquadMSUser::resolveRelationUsing('serverChatMessages', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(ServerChatMessage::class);
        });

        SquadMSUser::resolveRelationUsing('bans', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(Ban::class);
        });

        SquadMSUser::resolveRelationUsing('banned', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(Ban::class, 'admin_id');
        });

        /* Middlewares */
        Route::aliasMiddleware('sqms-worker-auth', WorkerAuth::class);
    }

    /**
     * The policy mappings for the application.
     *
     * @return array
     */
    public function policies()
    {
        return [
            Server::class => ServerPolicy::class,
        ];
    }

    public function addNavigationTypes(): void
    {
        SquadMSNavigation::addType('Servers', fn () => route('servers'));
        SquadMSNavigation::addType('Server', fn (array $data) => route('server', [
            'server' => $data['server_id'],
        ]), [
            Select::make('server_id')
                ->searchable()
                ->options(fn () => Server::pluck('title', 'id')),
        ]);
    }

    public function schedule(Schedule $schedule): void
    {
        foreach (Server::all() as $server) {
            $schedule->job(new QueryServer($server))->withoutOverlapping()->everyMinute();
        }
    }
}
