<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('勤怠管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- 通知バー -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- メンバー選択 -->
                    <form method="GET" action="{{ route('attendances.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                            <select name="member_id" id="member_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                <option value="">全ての従業員</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" @if(request('member_id') == $member->id) selected @endif>{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">開始日</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">終了日</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3 mt-2">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">検索</button>
                        </div>
                    </form>

                    <!-- 勤怠テーブル -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">日付</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">出勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">退勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">従業員名</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">休憩時間</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">備考</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @include('attendances._table_rows', ['attendances' => $attendances])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
