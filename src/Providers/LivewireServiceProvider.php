<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use SquadMS\Servers\Admin\Http\Livewire\Server\ServerChat;
use SquadMS\Servers\Admin\Http\Livewire\Servers\CreateServer;
use SquadMS\Servers\Admin\Http\Livewire\Servers\ServerList;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Register livewire components */
        Livewire::component('sqms-servers.admin.servers.server-list', ServerList::class);
        Livewire::component('sqms-servers.admin.servers.create-server', CreateServer::class);
        Livewire::component('sqms-servers.admin.server.chat', ServerChat::class);
    }
}
