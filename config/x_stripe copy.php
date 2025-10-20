<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    'plans' => [
        'free' => env('STRIPE_PLAN_FREE'),
        'standard' => env('STRIPE_PLAN_STANDARD'),
        // 'premium' => env('STRIPE_PLAN_PREMIUM'),
    ],
];
