<?php

namespace ConduitUI\Calendar\Drivers\Google;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class GoogleCalendarConnector extends Connector
{
    public function __construct(private readonly string $accessToken) {}

    public function resolveBaseUrl(): string
    {
        return 'https://www.googleapis.com/calendar/v3';
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->accessToken);
    }

    protected function defaultHeaders(): array
    {
        return ['Accept' => 'application/json'];
    }
}
