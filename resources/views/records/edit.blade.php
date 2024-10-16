<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record 編集') }}
        </h2>
    </x-slot>
    

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
                                    <label for="check_item" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Check Item</label>
                                    <input type="radio" name="check_item" value="1" {{ $record->check_item == 1 ? 'checked' : '' }}> はい
                                    <input type="radio" name="check_item" value="0" {{ $record->check_item == 0 ? 'checked' : '' }}> いいえ
                                </div>
                            @else
                                <input type="hidden" name="check_item" value="">
                            @endif

                            {{-- has_content フィールド --}}
                            @if ($template->has_content)
                                <div class="mb-4">
                                    <label for="content_item" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Content Item</label>
                                    <input type="text" name="content_item" id="content_item" value="{{ $record->content_item }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            @else
                                <input type="hidden" name="content_item" value="">
                            @endif

                            {{-- has_photo フィールド --}}
                            @if ($template->has_photo)
                                <div class="mb-4">
                                    <label for="photo_item" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Photo Item</label>
                                    <input type="file" name="photo_item" id="photo_item" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            @else
                                <input type="hidden" name="photo_item" value="">
                            @endif

                            {{-- has_temperature フィールド --}}
                            @if ($template->has_temperature)
                                <div class="mb-4">
                                    <label for="temperature_item" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Temperature Item</label>
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
