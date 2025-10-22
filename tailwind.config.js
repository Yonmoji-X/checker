import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    safelist: [
        // フリープラン（グレー）
        'bg-gray-50', 'dark:bg-gray-900', 'text-gray-700', 'dark:text-gray-300', 'bg-gray-400', 'hover:bg-gray-500', 'text-white',
        
        // ライトプラン（緑）
        'bg-green-50', 'dark:bg-green-900', 'text-green-700', 'dark:text-green-300', 'bg-green-600', 'hover:bg-green-700', 'text-white',

        // スタンダードプラン（青）
        'bg-blue-50', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300', 'bg-blue-600', 'hover:bg-blue-700', 'text-white',

        // プレミアムプラン（黄金）
        'bg-yellow-50', 'dark:bg-yellow-900', 'text-yellow-700', 'dark:text-yellow-300', 'bg-yellow-600', 'hover:bg-yellow-700', 'text-white',

        // 予備：オレンジ
        'bg-orange-50', 'dark:bg-orange-900', 'text-orange-700', 'dark:text-orange-300', 'bg-orange-600', 'hover:bg-orange-700', 'text-white',
    ],




    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
