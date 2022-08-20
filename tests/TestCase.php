<?php

namespace SquadMS\Servers\Tests;

use SquadMS\Foundation\Tests\TestCase as SquadMSFoundationTestCase;

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
