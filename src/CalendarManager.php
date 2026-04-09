<?php

namespace ConduitUI\Calendar;

use ConduitUI\Calendar\Contracts\CalendarProvider;
use ConduitUI\Calendar\Drivers\Google\GoogleCalendarDriver;

class CalendarManager
{
    /** @var array<string, CalendarProvider> */
    private array $drivers = [];

    public function driver(?string $name = null): CalendarProvider
    {
        $name ??= config('calendar.default', 'google');

        return $this->drivers[$name] ??= $this->resolve($name);
    }

    private function resolve(string $name): CalendarProvider
    {
        $config = config("calendar.drivers.{$name}");

        return match ($name) {
            'google' => new GoogleCalendarDriver($config['access_token'] ?? ''),
            default => throw new \InvalidArgumentException("Calendar driver [{$name}] is not supported."),
        };
    }
}
