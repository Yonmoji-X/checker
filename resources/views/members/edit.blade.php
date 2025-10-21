<!-- resources/views/members/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('名簿編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('members.show', $member) }}" class="text-blue-500 hover:text-blue-700 mr-2">詳細に戻る</a>

                    <form method="POST" action="{{ route('members.update', $member) }}">
                        @csrf
                        @method('PUT')

                        <!-- 名前 -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">メンバー編集</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('name')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- メールアドレス（任意） -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">メールアドレス</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('email')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 備考（任意） -->
                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">備考</label>
                            <textarea name="content" id="content" rows="4" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $member->content) }}</textarea>
                            @error('content')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 表示設定 -->
                        <div class="mb-4">
                            <label for="is_visible" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">表示設定</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_visible" id="is_visible" value="1"
                                    {{ old('is_visible', $member->is_visible) ? 'checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">表示</span>
                            </label>
                        </div>

                        <!-- 更新ボタン -->
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            更新
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
