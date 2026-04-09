<?php

namespace ConduitUI\Calendar\Contracts;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class CalendarEvent extends Data
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public Carbon $start,
        public Carbon $end,
        public bool $allDay,
        public ?string $location,
        /** @var array<int, array{email: string, name?: string, status?: string}> */
        public array $attendees,
        public string $status,
        public ?string $htmlLink,
    ) {}
}
