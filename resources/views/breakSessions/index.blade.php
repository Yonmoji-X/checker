<!-- resources/views/breaksessions/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('breaksession一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            @foreach ($breaksessions as $breaksession)
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <!-- <p class="text-gray-600 dark:text-gray-400 text-sm">member_id: {{ $breaksession->member_id }}</p> -->
                <p class="text-gray-600 dark:text-gray-400 text-sm">メンバー: {{ optional($breaksession->member)->name ?? '不明なメンバー' }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">休憩開始: {{ $breaksession->break_in }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">休憩終了: {{ $breaksession->break_out }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">休憩時間: {{ $breaksession->	break_duration }}分</p>
                <!-- <a href="{{ route('breaksessions.show', $breaksession) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a> -->
            </div>
            @endforeach
            </div>
        </div>
        </div>
    </div>

</x-app-layout>

