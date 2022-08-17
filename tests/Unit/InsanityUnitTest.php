<?php

namespace SquadMS\Servers\Tests\Unit;

use SquadMS\Servers\Data\ServerQueryResult;
use SquadMS\Servers\Tests\TestCase;

class InsanityUnitTest extends TestCase
{
    /**
     * Just a test so tests do not fail.
     *
     * @return void
     */
    public function test_can_instantiate_object()
    {
        new ServerQueryResult();

        $this->assertTrue(true);
    }
}
