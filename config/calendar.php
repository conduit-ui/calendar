<?php

return [
    'default' => env('CALENDAR_DRIVER', 'google'),

    'drivers' => [
        'google' => [
            'access_token' => env('GOOGLE_CALENDAR_ACCESS_TOKEN'),
        ],
    ],
];
