<?php

namespace ConduitUI\Calendar\Drivers\Google\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListCalendars extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/users/me/calendarList';
    }
}
