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
            'limit' => 1, // ←ここに上限を直接定義
            'bg' => 'bg-green-50 dark:bg-green-900',
            'text' => 'text-green-700 dark:text-green-300',
            'button' => 'bg-gray-400 hover:bg-gray-500 text-white',
        ],
        [
            'name' => 'スタンダードプラン',
            'price' => '¥1,980',
            'key' => 'standard',
            'stripe_plan' => env('STRIPE_PLAN_STANDARD'),
            'limit' => 30, // ←上限
            'bg' => 'bg-blue-50 dark:bg-blue-900',
            'text' => 'text-blue-700 dark:text-blue-300',
            'button' => 'bg-blue-600 hover:bg-blue-700 text-white',
        ],
        // プレミアムなど無制限プランも同様
        /*
        [
            'name' => 'プレミアムプラン',
            'price' => '¥10,000',
            'key' => 'premium',
            'stripe_plan' => env('STRIPE_PLAN_PREMIUM'),
            'limit' => null, // 無制限
            'bg' => 'bg-purple-50 dark:bg-purple-900',
            'text' => 'text-purple-700 dark:text-purple-300',
            'button' => 'bg-purple-600 hover:bg-purple-700 text-white',
        ],
        */
    ],
];
