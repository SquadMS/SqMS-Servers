<?php

namespace SquadMS\Servers;

use SquadMS\Foundation\Modularity\Contracts\SquadMSModule as SquadMSModuleContract;

class SquadMSModule extends SquadMSModuleContract {
    static function getIdentifier() : string
    {
        return 'sqms-servers';
    }

    static function getName() : string
    {
        return 'SquadMS Servers';
    }

    static function publishAssets() : void
    {
        //
    }
}