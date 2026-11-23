<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function actingAsAdmin(): static
    {
        $this->withSession([
            config('admin.session_key') => true,
        ]);

        return $this;
    }
}
