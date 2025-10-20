<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    // 単純な price_id 用
    'plans' => [
        'free' => env('STRIPE_PLAN_FREE'),
        'standard' => env('STRIPE_PLAN_STANDARD'),
        // 'premium' => env('STRIPE_PLAN_PREMIUM'),
    ],

    // プランカードで使う表示情報
    'plans_list' => [
        [
            'name' => 'フリープラン',
            'price' => '¥0',
            'key' => 'free',
            'price_id' => env('STRIPE_PLAN_FREE'),
            'bg' => 'bg-green-50 dark:bg-green-900',
            'text' => 'text-green-700 dark:text-green-300',
            'button' => 'bg-gray-400 hover:bg-gray-500 text-white',
        ],
        [
            'name' => 'スタンダードプラン',
            'price' => '¥1,980',
            'key' => 'standard',
            'price_id' => env('STRIPE_PLAN_STANDARD'),
            'bg' => 'bg-blue-50 dark:bg-blue-900',
            'text' => 'text-blue-700 dark:text-blue-300',
            'button' => 'bg-blue-600 hover:bg-blue-700 text-white',
        ],
    ],
];
