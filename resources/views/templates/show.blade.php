
<!-- resources/views/templates/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Item詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <a href="{{ route('templates.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>
            <p class="text-gray-800 dark:text-gray-300 text-lg">{{ $template->template }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">項目名: {{ $template->title }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm"> {{ $template->member_status ? '管理者':'従業員' }}用</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm"> {{ $template->clock_status ? '出勤時':'退勤時' }}</p>
            <ul class="text-gray-600 dark:text-gray-400 text-sm">
                <li>・Check 欄: {{ $template->has_check ? '✅':'-' }}</li>
                <li>・写真　欄: {{ $template->has_photo ? '✅':'-' }}</li>
                <li>・文章　欄: {{ $template->has_content ? '✅':'-' }}</li>
                <li>・温度　欄: {{ $template->has_temperature ? '✅':'-' }}</li>
            </ul>
            <p class="text-gray-600 dark:text-gray-400 text-sm">表示: {{ $template->hide ? '非表示' : '表示' }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $template->user->name }}</p>
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                <p>作成日時: {{ $template->created_at->format('Y-m-d H:i') }}</p>
                <p>更新日時: {{ $template->updated_at->format('Y-m-d H:i') }}</p>
            </div>
            @if (auth()->id() == $template->user_id)
            <div class="flex mt-4">
                <a href="{{ route('templates.edit', $template) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
                <form action="{{ route('templates.destroy', $template) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <!-- <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
                </form> -->
            </div>
            @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
