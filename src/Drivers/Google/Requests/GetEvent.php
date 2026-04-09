<?php

namespace ConduitUI\Calendar\Drivers\Google\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetEvent extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $calendarId,
        private readonly string $eventId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/calendars/{$this->calendarId}/events/{$this->eventId}";
    }
}
