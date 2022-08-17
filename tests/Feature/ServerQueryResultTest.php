<?php

namespace SquadMS\Servers\Tests\Feature;

use SquadMS\Servers\Data\ServerQueryResult;
use SquadMS\Servers\Models\Server;

class ServerQueryResultTest extends TestCase
{
    /**
     * Just a test so tests do not fail.
     *
     * @return void
     */
    public function test_it_can_instantiate_the_class()
    {
        $server = Server::create([
            'name'       => 'Squad Dedicated Server',
            'host'       => '127.0.0.1',
            'port'       => 7787,
            'query_port' => 27165
        ]);

        new ServerQueryResult(
            server: $server,
            name: $server->name
        );

        $this->assertTrue(true);
    }
}
