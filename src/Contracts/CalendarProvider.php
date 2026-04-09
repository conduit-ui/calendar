<?php

namespace ConduitUI\Calendar\Contracts;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface CalendarProvider
{
    /** @return Collection<int, CalendarEvent> */
    public function listCalendars(): Collection;

    /** @return Collection<int, CalendarEvent> */
    public function listEvents(string $calendarId, Carbon $start, Carbon $end, int $maxResults = 20): Collection;

    public function getEvent(string $calendarId, string $eventId): CalendarEvent;

    public function createEvent(string $calendarId, array $data): CalendarEvent;
}
