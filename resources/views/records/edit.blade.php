<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('チェック内容 編集') }}
        </h2>
    </x-slot>

    <style>
        .check-li {
            margin-bottom: 10px;
        }
        .radio-group {
            display: flex;
            gap: 20px; /* 2つの要素の間に間隔を追加 */
        }

        .form-radio {
            display: none;
        }

        .radio-label {
            background-color: rgb(31, 41, 55 / var(--tw-bg-opacity)); /* ダークグレー */
            color: #d1d5db; /* ライトグレーの文字色 */
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 50px; /* 完全に丸く */
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); /* 深めのシャドウで立体感 */
            transition: background-color 0.4s ease, color 0.4s ease, transform 0.3s ease; /* スムーズなトランジション */
        }

        .radio-label:hover {
            background-color: #6b5bff; /* 明るい紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #6b5bff, #8a74ff); /* 鮮やかな紫のグラデーション */
            transform: translateY(-2px); /* ホバー時に少し上に移動 */
        }

        .form-radio:checked + .radio-label {
            background-color: #8a44e4; /* 鮮やかな紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #b057ff, #007bff);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* 選択時のシャドウを強化 */
        }

        .form-radio:checked + .radio-label:hover {
            background-color: #9b57ff; /* さらに明るい紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #9b57ff, #bb81ff); /* ホバー時のより明るい輝き */
            transform: translateY(-2px); /* ホバー時に少し上に移動 */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('records.index', $record) }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>

                    <form method="POST" action="{{ route('records.update', $record) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($template)
                            <p>{{ $template->title }}</p>

                            {{-- has_check フィールド --}}
                            @if ($template->has_check)
                                <div class="mb-4">
                                    <div class="radio-group">
                                        <input type="radio" id="check_yes" name="check_item" value="1" class="form-radio" {{ $record->check_item == 1 ? 'checked' : '' }}>
                                        <label for="check_yes" class="radio-label">はい</label>

                                        <input type="radio" id="check_no" name="check_item" value="0" class="form-radio" {{ $record->check_item == 0 ? 'checked' : '' }}>
                                        <label for="check_no" class="radio-label">いいえ</label>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="check_item" value="">
                            @endif

                            {{-- has_content フィールド --}}
                            @if ($template->has_content)
                                <div class="mb-4">
                                    <input type="text" name="content_item" id="content_item" value="{{ $record->content_item }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            @else
                                <input type="hidden" name="content_item" value="">
                            @endif

                            {{-- has_photo フィールド --}}
                            @if ($template->has_photo)
                                <div class="mb-4">
                                    <input type="file" name="photo_item" id="photo_item" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            @else
                                <input type="hidden" name="photo_item" value="">
                            @endif

                            {{-- has_temperature フィールド --}}
                            @if ($template->has_temperature)
                                <div class="mb-4">
                                    <input type="number" step="0.1" name="temperature_item" id="temperature_item" value="{{ $record->temperature_item }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            @else
                                <input type="hidden" name="temperature_item" value="">
                            @endif

                        @else
                            <p>Template not found</p>
                        @endif

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">編集完了</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
