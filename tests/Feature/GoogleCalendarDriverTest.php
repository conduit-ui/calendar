<?php

use ConduitUI\Calendar\Contracts\CalendarEvent;
use ConduitUI\Calendar\Drivers\Google\GoogleCalendarConnector;
use ConduitUI\Calendar\Drivers\Google\GoogleCalendarDriver;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('GoogleCalendarDriver', function () {
    it('lists events and returns CalendarEvent DTOs', function () {
        $mockClient = new MockClient([
            '*' => MockResponse::make([
                'items' => [
                    [
                        'id' => 'evt-1',
                        'summary' => 'Team standup',
                        'start' => ['dateTime' => '2026-04-09T09:00:00-07:00'],
                        'end' => ['dateTime' => '2026-04-09T09:30:00-07:00'],
                        'location' => 'Zoom',
                        'status' => 'confirmed',
                        'htmlLink' => 'https://calendar.google.com/event/evt-1',
                        'attendees' => [
                            ['email' => 'jordan@test.com', 'displayName' => 'Jordan', 'responseStatus' => 'accepted'],
                        ],
                    ],
                    [
                        'id' => 'evt-2',
                        'summary' => 'Lunch',
                        'start' => ['date' => '2026-04-09'],
                        'end' => ['date' => '2026-04-09'],
                        'status' => 'confirmed',
                    ],
                ],
            ]),
        ]);

        $connector = new GoogleCalendarConnector('fake-token');
        $connector->withMockClient($mockClient);

        $driver = new GoogleCalendarDriver('fake-token');
        // Use reflection to swap connector
        $ref = new ReflectionProperty($driver, 'connector');
        $ref->setValue($driver, $connector);

        $events = $driver->listEvents('primary', now()->startOfDay(), now()->endOfDay());

        expect($events)->toHaveCount(2);
        expect($events[0])->toBeInstanceOf(CalendarEvent::class);
        expect($events[0]->title)->toBe('Team standup');
        expect($events[0]->location)->toBe('Zoom');
        expect($events[0]->allDay)->toBeFalse();
        expect($events[0]->attendees)->toHaveCount(1);

        expect($events[1]->title)->toBe('Lunch');
        expect($events[1]->allDay)->toBeTrue();
        expect($events[1]->location)->toBeNull();
    });

    it('returns empty collection when no events', function () {
        $mockClient = new MockClient([
            '*' => MockResponse::make(['items' => []]),
        ]);

        $connector = new GoogleCalendarConnector('fake-token');
        $connector->withMockClient($mockClient);

        $driver = new GoogleCalendarDriver('fake-token');
        $ref = new ReflectionProperty($driver, 'connector');
        $ref->setValue($driver, $connector);

        $events = $driver->listEvents('primary', now()->startOfDay(), now()->endOfDay());

        expect($events)->toBeEmpty();
    });

    it('lists calendars', function () {
        $mockClient = new MockClient([
            '*' => MockResponse::make([
                'items' => [
                    ['id' => 'primary', 'summary' => 'Jordan', 'primary' => true],
                    ['id' => 'work@test.com', 'summary' => 'Work'],
                ],
            ]),
        ]);

        $connector = new GoogleCalendarConnector('fake-token');
        $connector->withMockClient($mockClient);

        $driver = new GoogleCalendarDriver('fake-token');
        $ref = new ReflectionProperty($driver, 'connector');
        $ref->setValue($driver, $connector);

        $calendars = $driver->listCalendars();

        expect($calendars)->toHaveCount(2);
        expect($calendars[0]['summary'])->toBe('Jordan');
        expect($calendars[0]['primary'])->toBeTrue();
    });
});
