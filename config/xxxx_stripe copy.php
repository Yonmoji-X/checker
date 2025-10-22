<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    'plans_list' => [
        [
            'name' => 'フリープラン',
            'price' => '¥0',
            'key' => 'free',
            'stripe_plan' => env('STRIPE_PLAN_FREE'),
            'limit' => 1,
            'bg' => 'bg-green-50 dark:bg-green-900',
            'text' => 'text-green-700 dark:text-green-300',
            'button' => 'bg-gray-400 hover:bg-gray-500 text-white',
        ],
        [
            'name' => 'ライトプラン',
            'price' => '¥980',
            'key' => 'light',
            'stripe_plan' => env('STRIPE_PLAN_LIGHT'),
            'limit' => 5,
            'bg' => 'bg-orange-50 dark:bg-orange-900',
            'text' => 'text-orange-700 dark:text-orange-300',
            'button' => 'bg-orange-600 hover:bg-orange-700 text-white',
        ],
        [
            'name' => 'スタンダードプラン',
            'price' => '¥1,980',
            'key' => 'standard',
            'stripe_plan' => env('STRIPE_PLAN_STANDARD'),
            'limit' => 30,
            'bg' => 'bg-blue-50 dark:bg-blue-900',
            'text' => 'text-blue-700 dark:text-blue-300',
            'button' => 'bg-blue-600 hover:bg-blue-700 text-white',
        ],
        [
            'name' => 'プレミアムプラン',
            'price' => '¥4,980',
            'key' => 'premium',
            'stripe_plan' => env('STRIPE_PLAN_PREMIUM'),
            'limit' => 100,
            'bg' => 'bg-yellow-50 dark:bg-yellow-900',  // 黄金っぽく
            'text' => 'text-yellow-700 dark:text-yellow-300',
            'button' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
        ],
    ],
];
