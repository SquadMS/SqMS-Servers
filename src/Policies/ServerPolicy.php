<?php

namespace SquadMS\Servers\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;
use SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Models\Server;

class ServerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     *
     * @return mixed
     */
    public function viewAny(SquadMSUser $user)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     * @param \SquadMS\Servers\Models\Server $server
     *
     * @return mixed
     */
    public function view(SquadMSUser $user, Server $server)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     *
     * @return mixed
     */
    public function create(SquadMSUser $user)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     * @param \SquadMS\Servers\Models\Server $server
     *
     * @return mixed
     */
    public function update(SquadMSUser $user, Server $server)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     * @param \SquadMS\Servers\Models\Server $server
     *
     * @return mixed
     */
    public function delete(SquadMSUser $user, Server $server)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     * @param \SquadMS\Servers\Models\Server $server
     *
     * @return mixed
     */
    public function restore(SquadMSUser $user, Server $server)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser; $user
     * @param \SquadMS\Servers\Models\Server $server
     *
     * @return mixed
     */
    public function forceDelete(SquadMSUser $user, Server $server)
    {
        return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
    }
}
