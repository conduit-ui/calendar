<?php

namespace ConduitUI\Calendar\Drivers\Google\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListEvents extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $calendarId,
        private readonly string $timeMin,
        private readonly string $timeMax,
        private readonly int $maxResults = 20,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/calendars/{$this->calendarId}/events";
    }

    protected function defaultQuery(): array
    {
        return [
            'timeMin' => $this->timeMin,
            'timeMax' => $this->timeMax,
            'maxResults' => $this->maxResults,
            'singleEvents' => 'true',
            'orderBy' => 'startTime',
        ];
    }
}
