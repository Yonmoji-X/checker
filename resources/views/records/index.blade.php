


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Records List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ url('/records/create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        Create New Record
                    </a>

                    <div class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                        <div class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach ($records as $record)
                                <ul>
                                    <!-- <li class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $record->id }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->user_id }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->member_id }}</li> -->
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">head_ID:{{ $record->head_id }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">テンプレートID:{{ $record->template_id }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->member_status ? '管理者' : '従業員' }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->clock_status ? '出勤' : '退勤' }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->check_item ? 'はい' : 'いいえ' }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->photo_item }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->content_item }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $record->temperature_item }}</li>
                                    <li class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ url('/records/' . $record->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">詳細</a>
                                        <a href="{{ url('/records/' . $record->id . '/edit') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mx-2">編集</a>
                                        <form action="{{ url('/records/' . $record->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">削除</button>
                                        </form>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
