<?php

namespace SquadMS\Servers\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SquadMS\Servers\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
}
