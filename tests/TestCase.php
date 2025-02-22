<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Prevent Telescope from running during tests
        if (class_exists(TelescopeServiceProvider::class)) {
            Telescope::stopRecording();
        }

        $this->seed(RoleSeeder::class);
    }
}
