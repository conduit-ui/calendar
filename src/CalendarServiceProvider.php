<?php

namespace ConduitUI\Calendar;

use Illuminate\Support\ServiceProvider;

class CalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/calendar.php', 'calendar');

        $this->app->singleton(CalendarManager::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/calendar.php' => config_path('calendar.php'),
            ], 'calendar-config');
        }
    }
}
