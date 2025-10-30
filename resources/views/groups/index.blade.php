<!-- resources/views/groups/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('管理下利用者アカウント') }}
        </h2>
    </x-slot>
    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)" 
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 transition-all duration-500"
        >
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)" 
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 transition-all duration-500"
        >
            {{ session('success') }}
        </div>
    @endif


    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 mt-6">
            <form method="POST" action="{{ route('groups.bulkRemove') }}">
                <div class="p-4 flex justify-end">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 transition"
                        onclick="return confirm('選択したユーザーを管理下から削除します。よろしいですか？');"
                    >
                        管理下から除外
                    </button>
                </div>
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">グループ</th> -->
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admin ID</th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ユーザーID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">氏名</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">メール</th>
                                    <!-- <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">操作</th> -->
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($groups as $group)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $group->group }}
                                        </td> -->
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $group->admin_id }}
                                        </td> -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            <input type="checkbox" name="group_ids[]" value="{{ $group->id }}">
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $group->user_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $group->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $group->email }}
                                        </td>
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <a href="{{ route('groups.show', $group) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-100 rounded-md 
                                                    hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                                詳細
                                            </a>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
