<!-- resources/views/templates/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Item一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            @foreach ($templates as $template)
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-800 dark:text-gray-300">{{ $template->template }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Item: {{ $template->title }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm"> {{ $template->member_status ? '管理者':'従業員' }}用</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm"> {{ $template->clock_status ? '出勤時':'退勤時' }}</p>
                <ul class="text-gray-600 dark:text-gray-400 text-sm">
                    <li>・Check 欄: {{ $template->has_check ? '✅':'-' }}</li>
                    <li>・写真　欄: {{ $template->has_photo ? '✅':'-' }}</li>
                    <li>・文章　欄: {{ $template->has_content ? '✅':'-' }}</li>
                    <li>・温度　欄: {{ $template->has_temperature ? '✅':'-' }}</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-400 text-sm">（投稿者: {{ $template->user->name }}）</p>
                <a href="{{ route('templates.show', $template) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
            </div>
            @endforeach
            </div>
        </div>
        </div>
    </div>

</x-app-layout>

