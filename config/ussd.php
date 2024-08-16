<?php

// use TNM\USSD\Screens\Welcome;
use App\Screens\Welcome;
use App\Screens\Newuser;
use App\Screens\Home;



return [
    'session' => [
        'last_activity_minutes' => 2,
    ],
    'routing' => [
        'prefix' => 'api/ussd',
        'middleware' => ['api'],
        'landing_screen' => Welcome::class,
        // 'newuser_screen' => Welcome::class,
    ],
    'navigation' => [
        'home' => '*',
        'previous' => '#'
    ],
    'default' => [
        'options' => ['Subscribe', 'Unsubscribe'],
        'welcome' => 'Welcome to the USSD App',
    ]
];
