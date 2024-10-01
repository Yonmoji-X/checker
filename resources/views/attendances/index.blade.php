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
                        <div id="success-notification" class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <a href="{{ url('/records/create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        チェック
                    </a>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                            <select name="member_id" id="member_id" onchange="filterDataRecords()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">全ての従業員</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">日付</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">出勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">退勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">従業員ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">休憩時間</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">備考</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" id="attendanceTableBody">
                                @foreach ($attendances as $attendance)
                                    <tr data-member-id="{{ $attendance->member_id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $attendance->attendance_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $attendance->clock_in }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $attendance->clock_out }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $attendance->member->name }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            @php
                                                $totalBreakDuration = $attendance->breakSessions->sum(function($breakSession) {
                                                    return $breakSession->break_out ? $breakSession->break_duration : 0;
                                                });
                                                $isCurrentlyOnBreak = $attendance->breakSessions->contains(function($breakSession) {
                                                    return $breakSession->break_in && is_null($breakSession->break_out);
                                                });
                                            @endphp
                                            {{ $totalBreakDuration }} 分
                                            @if ($isCurrentlyOnBreak)
                                                <span class="text-red-500">（休憩中）</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <form action="{{ url('/attendances/' . $attendance->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="attendance" value="{{ $attendance->attendance }}" class="border border-gray-300 rounded-md px-2 py-1 dark:bg-gray-700 focus:outline-none focus:shadow-outline" required>
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 ml-2">保存</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeletion() {
            return confirm("関連する休憩データも削除されます。本当に削除しますか？");
        }

        function filterDataRecords() {
            const selectedMemberId = document.getElementById('member_id').value;
            const rows = document.querySelectorAll('#attendanceTableBody tr');
            rows.forEach(row => {
                const memberId = row.getAttribute('data-member-id');
                if (selectedMemberId === "" || memberId === selectedMemberId) {
                    row.style.display = ''; // 行を表示
                } else {
                    row.style.display = 'none'; // 行を非表示
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // 初期状態で全ての従業員のデータを表示
            filterDataRecords();

            const notification = document.getElementById('success-notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</x-app-layout>

