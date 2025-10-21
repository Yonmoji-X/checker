<!-- resources/views/members/index.blade.php -->

<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('名簿') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 mt-6">

            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 max-w-xl">
                    <!-- 上段：左右に配置 -->
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">現在のプラン: <span class="font-bold">{{ $planName }}</span></h3>

                        @if($limit)
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                登録済み: <span class="font-semibold">{{ $memberCount }}</span> / <span class="font-semibold">{{ $limit }}</span>
                            </p>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-300">登録数無制限</p>
                        @endif
                    </div>

                    <!-- 下段：全幅に配置 -->
                    @if($limit)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            このプランでは最大<span class="font-semibold">{{ $limit }}</span>名登録できます。
                        </p>
                    @endif
                </div>
            </div>


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
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 {{ $member->is_over_limit ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $member->is_visible ? '💡' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $member->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $member->email }}
                                </td>
                                <td class="relative px-6 py-4 text-sm w-[250px]">
                                    <span class="truncate cursor-pointer" title="{{ $member->content }}">
                                        {{ Str::limit($member->content, 20) }}
                                    </span>
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

{{-- 名簿上限エラーモーダル --}}
@if (session('error'))
    <x-member-max-modal 
        :message="session('error')" 
        :change-plan-route="route('checkout')" 
    />
@endif
