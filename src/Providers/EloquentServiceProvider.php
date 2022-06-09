<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Models\Ban;
use SquadMS\Servers\Models\ServerChatMessage;

class EloquentServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        SquadMSUser::resolveRelationUsing('serverChatMessages', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(ServerChatMessage::class);
        });

        SquadMSUser::resolveRelationUsing('bans', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(Ban::class);
        });

        SquadMSUser::resolveRelationUsing('banned', static function (SquadMSUser $user): HasMany {
            return $user->hasMany(Ban::class, 'admin_id');
        });
    }
}
