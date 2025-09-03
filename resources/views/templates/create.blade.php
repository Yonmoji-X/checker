<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('アイテム作成') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 w-full max-w-xl">

                <form method="POST" action="{{ route('templates.store') }}">
                    @csrf

                    <!-- タイトル -->
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">タイトル</label>
                        <input type="text" name="title" id="title"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                   focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            value="{{ old('title') }}">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 管理者/従業員 & 出勤時/退勤時 横並び -->
                    <div class="mb-4 flex gap-4">
                        <div class="flex-1">
                            <label for="member_status" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">管理者/従業員</label>
                            <select name="member_status" id="member_status"
                                class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                       focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="0" {{ old('member_status') == '0' ? 'selected' : '' }}>従業員</option>
                                <option value="1" {{ old('member_status') == '1' ? 'selected' : '' }}>管理者</option>
                            </select>
                            @error('member_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-1">
                            <label for="clock_status" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">出勤時/退勤時</label>
                            <select name="clock_status" id="clock_status"
                                class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                       focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="1" {{ old('clock_status') == '1' ? 'selected' : '' }}>出勤時</option>
                                <option value="0" {{ old('clock_status') == '0' ? 'selected' : '' }}>退勤時</option>
                            </select>
                            @error('clock_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- チェックボタンの有無 -->
                    <div class="mb-4">
                        <label for="has_check" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">チェックボタンの有無</label>
                        <select name="has_check" id="has_check"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                   focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="0" {{ old('has_check') == '0' ? 'selected' : '' }}>無</option>
                            <option value="1" {{ old('has_check') == '1' ? 'selected' : '' }}>有</option>
                        </select>
                        @error('has_check')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 写真投稿の有無（固定値0） -->
                    <input type="hidden" name="has_photo" value="0">
                    <!-- 写真投稿の有無 -->
                    <!-- <div class="mb-4">
                        <label for="has_photo" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">写真投稿の有無</label>
                        <select name="has_photo" id="has_photo"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="0" {{ old('has_photo') == '0' ? 'selected' : '' }}>無</option>
                            <option value="1" {{ old('has_photo') == '1' ? 'selected' : '' }}>有</option>
                        </select>
                        @error('has_photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> -->


                    <!-- テキスト記入欄の有無 -->
                    <div class="mb-4">
                        <label for="has_content" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">テキスト記入欄の有無</label>
                        <select name="has_content" id="has_content"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                   focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="0" {{ old('has_content') == '0' ? 'selected' : '' }}>無</option>
                            <option value="1" {{ old('has_content') == '1' ? 'selected' : '' }}>有</option>
                        </select>
                        @error('has_content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 温度記入欄の有無 -->
                    <div class="mb-4">
                        <label for="has_temperature" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">温度記入欄の有無</label>
                        <select name="has_temperature" id="has_temperature"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                   focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="0" {{ old('has_temperature') == '0' ? 'selected' : '' }}>無</option>
                            <option value="1" {{ old('has_temperature') == '1' ? 'selected' : '' }}>有</option>
                        </select>
                        @error('has_temperature')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 送信ボタン -->
                    <div class="mt-6 flex justify-center">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200">
                            登録
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
