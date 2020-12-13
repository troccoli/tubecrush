<?php

namespace Tests;

use Database\Seeders\Testing\DatabaseSeeder;

trait SeedsTestData
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }
}
