<!-- resources/views/members/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('名簿') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <!-- 横スクロール対応のラッパー -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">表示</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">氏名</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">メール</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">備考</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($members as $member)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $member->is_visible ? '💡' : '  -' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $member->email }}
                                    </td>
                                    <td class="relative px-6 py-4 text-sm text-gray-900 dark:text-gray-200 group w-[250px]">
                                        <span class="truncate cursor-pointer" title="{{ $member->content }}">
                                            {{ Str::limit($member->content, 20) }}
                                        </span>
                                        <!-- ホバー時に表示されるツールチップ -->
                                        <!-- <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 hidden group-hover:block 
                                                    w-64 max-h-40 overflow-auto bg-gray-800 text-white text-xs rounded py-2 px-3 z-50 
                                                    whitespace-pre-wrap break-words shadow-lg">
                                            {{ $member->content }}
                                        </div> -->
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <a href="{{ route('members.show', $member) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                        詳細
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 p-4">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
