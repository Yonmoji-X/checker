<!-- resources/views/members/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('名簿詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <a href="{{ route('members.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>
            <p class="text-gray-800 dark:text-gray-300 text-lg">{{ $member->member }}</p>
            <p class="text-gray-800 dark:text-gray-300">{{ $member->member }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">氏名: {{ $member->name }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">email: {{ $member->email }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">備考: {{ $member->content }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $member->user->name }}</p>
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                <p>作成日時: {{ $member->created_at->format('Y-m-d H:i') }}</p>
                <p>更新日時: {{ $member->updated_at->format('Y-m-d H:i') }}</p>
            </div>
            @if (auth()->id() == $member->user_id)
            <div class="flex mt-4">
                <a href="{{ route('members.edit', $member) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
                <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <!-- <button type="submit" class="text-red-500 hover:text-red-700">削除</button> -->
                </form>
            </div>
            @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
