<?php

namespace ConduitUI\Calendar\Drivers\Google;

use ConduitUI\Calendar\Contracts\CalendarEvent;
use ConduitUI\Calendar\Contracts\CalendarProvider;
use ConduitUI\Calendar\Drivers\Google\Requests\GetEvent;
use ConduitUI\Calendar\Drivers\Google\Requests\ListCalendars;
use ConduitUI\Calendar\Drivers\Google\Requests\ListEvents;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GoogleCalendarDriver implements CalendarProvider
{
    private GoogleCalendarConnector $connector;

    public function __construct(string $accessToken)
    {
        $this->connector = new GoogleCalendarConnector($accessToken);
    }

    public function listCalendars(): Collection
    {
        $response = $this->connector->send(new ListCalendars);

        return collect($response->json('items', []))->map(fn (array $cal) => [
            'id' => $cal['id'],
            'summary' => $cal['summary'] ?? '',
            'primary' => $cal['primary'] ?? false,
        ]);
    }

    public function listEvents(string $calendarId, Carbon $start, Carbon $end, int $maxResults = 20): Collection
    {
        $response = $this->connector->send(new ListEvents(
            $calendarId,
            $start->toRfc3339String(),
            $end->toRfc3339String(),
            $maxResults,
        ));

        return collect($response->json('items', []))->map(fn (array $event) => $this->mapEvent($event));
    }

    public function getEvent(string $calendarId, string $eventId): CalendarEvent
    {
        $response = $this->connector->send(new GetEvent($calendarId, $eventId));

        return $this->mapEvent($response->json());
    }

    public function createEvent(string $calendarId, array $data): CalendarEvent
    {
        // TODO: Implement when write support is needed
        throw new \RuntimeException('Calendar event creation not yet implemented');
    }

    private function mapEvent(array $event): CalendarEvent
    {
        $startRaw = $event['start']['dateTime'] ?? $event['start']['date'] ?? now()->toIso8601String();
        $endRaw = $event['end']['dateTime'] ?? $event['end']['date'] ?? now()->toIso8601String();
        $allDay = isset($event['start']['date']) && ! isset($event['start']['dateTime']);

        return CalendarEvent::from([
            'id' => $event['id'] ?? '',
            'title' => $event['summary'] ?? 'Untitled',
            'description' => $event['description'] ?? null,
            'start' => Carbon::parse($startRaw),
            'end' => Carbon::parse($endRaw),
            'allDay' => $allDay,
            'location' => $event['location'] ?? null,
            'attendees' => collect($event['attendees'] ?? [])->map(fn (array $a) => [
                'email' => $a['email'] ?? '',
                'name' => $a['displayName'] ?? '',
                'status' => $a['responseStatus'] ?? 'needsAction',
            ])->all(),
            'status' => $event['status'] ?? 'confirmed',
            'htmlLink' => $event['htmlLink'] ?? null,
        ]);
    }
}
