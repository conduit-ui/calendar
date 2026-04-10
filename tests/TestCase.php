<?php

namespace ConduitUI\Calendar\Tests;

use ConduitUI\Calendar\CalendarServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            CalendarServiceProvider::class,
        ];
    }
}
