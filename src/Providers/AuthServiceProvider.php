<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Policies\ServerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Server::class => ServerPolicy::class,
    ];

    public function register()
    {
        //
    }

    public function boot()
    {
        /* Permissions */
        $this->registerPolicies();
    }
}
