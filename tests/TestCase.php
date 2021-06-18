<?php

namespace SquadMS\Servers\Tests;

use \SquadMS\Foundation\Tests\TestCase as SquadMSFoundationTestCase;

class TestCase extends SquadMSFoundationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [
            // Package Providers

            // SquadMS Servers Providers
            \SquadMS\Servers\SquadMSServersServiceProvider::class,
            \SquadMS\Servers\Providers\AuthServiceProvider::class,
            \SquadMS\Servers\Providers\LivewireServiceProvider::class,
            \SquadMS\Servers\Providers\ModulesServiceProvider::class,
            \SquadMS\Servers\Providers\PermissionsServiceProvider::class,
            \SquadMS\Servers\Providers\RouteServiceProvider::class,
            \SquadMS\Servers\Providers\ScheduleServiceProvider::class,
            \SquadMS\Servers\Providers\ViewServiceProvider::class,
        ]);
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        config()->set('localized-routes.supported-locales', [
            'en',
            'de',
        ]);
    }
}