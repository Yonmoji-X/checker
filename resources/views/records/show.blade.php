<!-- resources/views/records/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('チェック済み項目詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <a href="{{ route('records.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>
            <p class="text-gray-800 dark:text-gray-300 text-lg">{{ $record->record }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $record->user->name }}</p>
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                <p>作成日時: {{ $record->created_at->format('Y-m-d H:i') }}</p>
                <p>更新日時: {{ $record->updated_at->format('Y-m-d H:i') }}</p>
            </div>
            @if (auth()->id() == $record->user_id)
            <div class="flex mt-4">
                <a href="{{ route('records.edit', $record) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
                <form action="{{ route('records.destroy', $record) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
                </form>
            </div>
            @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
