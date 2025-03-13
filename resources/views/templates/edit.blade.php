<!-- resources/views/templates/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Item編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('templates.show', $template) }}" class="text-blue-500 hover:text-blue-700 mr-2">詳細に戻る</a>
                    <form method="POST" action="{{ route('templates.update', $template) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">タイトル</label>
                            <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $template->title) }}">
                            @error('title')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="member_status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">管理者/従業員</label>
                            <select name="member_status" id="member_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{ old('member_status', $template->member_status) == '0' ? 'selected' : '' }}>従業員</option>
                                <option value="1" {{ old('member_status', $template->member_status) == '1' ? 'selected' : '' }}>管理者</option>
                            </select>
                            @error('member_status')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="clock_status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">出勤時/退勤時</label>
                            <select name="clock_status" id="clock_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1" {{ old('clock_status', $template->clock_status) == '1' ? 'selected' : '' }}>出勤時</option>
                                <option value="0" {{ old('clock_status', $template->clock_status) == '0' ? 'selected' : '' }}>退勤時</option>
                            </select>
                            @error('clock_status')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="has_check" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">チェックボタンの有無</label>
                            <select name="has_check" id="has_check" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{ old('has_check', $template->has_check) == '0' ? 'selected' : '' }}>無</option>
                                <option value="1" {{ old('has_check', $template->has_check) == '1' ? 'selected' : '' }}>有</option>
                            </select>
                            @error('has_check')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="has_photo" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">写真投稿の有無</label>
                            <select name="has_photo" id="has_photo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{ old('has_photo', $template->has_photo) == '0' ? 'selected' : '' }}>無</option>
                                <option value="1" {{ old('has_photo', $template->has_photo) == '1' ? 'selected' : '' }}>有</option>
                            </select>
                            @error('has_photo')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="has_content" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">テキスト記入欄の有無</label>
                            <select name="has_content" id="has_content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{ old('has_content', $template->has_content) == '0' ? 'selected' : '' }}>無</option>
                                <option value="1" {{ old('has_content', $template->has_content) == '1' ? 'selected' : '' }}>有</option>
                            </select>
                            @error('has_content')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="has_temperature" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">温度記入欄の有無</label>
                            <select name="has_temperature" id="has_temperature" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{ old('has_temperature', $template->has_temperature) == '0' ? 'selected' : '' }}>無</option>
                                <option value="1" {{ old('has_temperature', $template->has_temperature) == '1' ? 'selected' : '' }}>有</option>
                            </select>
                            @error('has_temperature')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">表示/非表示</label>
                            <div>
                                <label>
                                    <input type="radio" name="hide" value="0" {{ old('hide', $template->hide) == '0' ? 'checked' : '' }}>
                                    表示
                                </label>
                                <label class="ml-4">
                                    <input type="radio" name="hide" value="1" {{ old('hide', $template->hide) == '1' ? 'checked' : '' }}>
                                    非表示
                                </label>
                            </div>
                            @error('hide')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">出力ファイル反映</label>
                            <div>
                                <label>
                                    <input type="radio" name="export" value="0" {{ old('export', $template->export) == '0' ? 'checked' : '' }}>
                                    反映しない
                                </label>
                                <label class="ml-4">
                                    <input type="radio" name="export" value="1" {{ old('export', $template->export) == '1' ? 'checked' : '' }}>
                                    反映する
                                </label>
                            </div>
                            @error('export')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">更新</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
