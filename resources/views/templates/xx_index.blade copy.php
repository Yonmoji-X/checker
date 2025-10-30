<!-- resources/views/templates/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('アイテム一覧') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @forelse ($templates as $template)
            <div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-3xl overflow-hidden transition-transform transform hover:scale-[1.02]">


                <!-- タイトルと右側ステータス -->
                <!-- タイトルと右側ステータスを縦並びに -->
                <div class="mb-3">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-right">
                        <span>{{ $template->hide ? '【非表示中】' : '【表示中】💡' }}</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $template->title }}</h3>
                </div>


                <!-- 左側ステータス -->
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-3 space-y-1">
                    <span>{{ $template->member_status ? '管理者' : '従業員' }}用</span>
                    <span>{{ $template->clock_status ? '出勤時' : '退勤時' }}</span>
                </div>

                <!-- 中央のチェックリスト -->
                <!-- <ul class="text-gray-600 dark:text-gray-300 text-base space-y-1 list-disc list-inside mb-3 text-center">
                    <li>Check欄: {{ $template->has_check ? '✅' : '-' }}</li>
                    <li>文章欄: {{ $template->has_content ? '✅' : '-' }}</li>
                    <li>温度欄: {{ $template->has_temperature ? '✅' : '-' }}</li>
                </ul> -->
                <!-- 中央のチェックリストプレビュー -->
                <div class="template mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600">

                    <!-- タイトル -->
                    <div class="mb-2">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-semibold">
                            {{ $template->title }}
                            <span style="color: red; font-size: 1.2em;">*</span>
                        </p>
                    </div>

                    <!-- チェックリストプレビュー -->
                    <ul class="text-gray-600 dark:text-gray-400 text-sm mt-2 list-none space-y-2">

                        @if($template->has_check)
                        <li class="flex gap-2">
                            <span class="radio-label inline-flex items-center bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full">はい</span>
                            <span class="radio-label inline-flex items-center bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full">いいえ</span>
                        </li>
                        @endif

                        @if($template->has_content)
                        <li>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 px-3 py-2 text-gray-800 dark:text-gray-200">
                                サンプル文章欄（プレビュー）
                            </div>
                        </li>
                        @endif

                        @if($template->has_photo)
                        <li>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 px-3 py-2 text-gray-800 dark:text-gray-200 text-center">
                                写真プレビュー
                            </div>
                        </li>
                        @endif

                        @if($template->has_temperature)
                        <li class="flex items-center gap-2">
                            <div class="border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 px-3 py-2 text-gray-800 dark:text-gray-200 w-20 text-center">
                                36.5
                            </div>
                            <span>℃</span>
                        </li>
                        @endif

                    </ul>

                </div>



                <!-- 小さい情報 -->
                <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1 mb-3">
                    <p>出力ファイル反映: {{ $template->export ? '✅' : '-' }}</p>
                    <p>投稿者: {{ $template->user->name }}</p>
                </div>

                <!-- 詳細リンク -->
                <div class="text-right">
                    <a href="{{ route('templates.show', $template) }}" class="inline-block text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                        詳細を見る →
                    </a>
                </div>

            </div>
                    @empty
        <div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-3xl text-center text-gray-500 dark:text-gray-400">
            まだ登録されていません。
        </div>
        @endforelse

        </div>
    </div>
</x-app-layout>
