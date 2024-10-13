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

                    <!-- 日付範囲選択カレンダー -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <!-- <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">日付範囲</label>
                            <input type="text" id="date_range" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="日付範囲を選択" />
                        </div> -->
                        <div>
    <label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">日付範囲</label>
    <input
        type="text"
        id="date_range"
        name="date_range"
        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        value="{{ $startDate }} から {{ $endDate }}"
        placeholder="日付範囲を選択"
    />
</div>


                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                            <select name="member_id" id="member_id" onchange="filterDataRecords()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">全ての従業員</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('attendance.export') }}" method="POST" onsubmit="return validateExportForm();">
                                @csrf
                                <input type="hidden" name="member_id" id="export_member_id" value="">
                                <input type="hidden" name="date_range" id="export_date_range" value="">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    勤怠データをエクスポート
                                </button>
                            </form>
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
                                    @if ($attendance->attendance_date >= $startDate && $attendance->attendance_date <= $endDate)
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
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $attendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script>
    // 日本時間を考慮した日付範囲選択
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        // 日本時間（JST）を考慮した初期データの範囲をセットして表示
        window.startDate = formatDateToJST(firstDayOfMonth);
        window.endDate = formatDateToJST(lastDayOfMonth);

        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "ja",
            defaultDate: [firstDayOfMonth, lastDayOfMonth],
            onClose: function(selectedDates, dateStr, instance) {
                document.getElementById('export_date_range').value = dateStr;

                if (selectedDates.length === 2) {
                    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };

                    // 再度日付範囲を変数に格納
                    window.startDate = formatDateToJST(selectedDates[0]);
                    window.endDate = formatDateToJST(selectedDates[1]);

                    console.log("選択された範囲の初めの日:", window.startDate);
                    console.log("選択された範囲の終わりの日:", window.endDate);

                    // 日付範囲が正しく更新されるたびにデータをフィルタリング
                    filterDataRecords();
                } else {
                    console.log("範囲が正しく選択されていません。");
                    // 日付範囲が正しく選択されなかった場合、変数をリセットする
                    window.startDate = undefined;
                    window.endDate = undefined;
                }
            }
        });

        // 初回ロード時にデータをフィルタリングして表示
        filterDataRecords();

        const notification = document.getElementById('success-notification');
        if (notification) {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    });

    function formatDateToJST(date) {
        // UTCから日本時間(JST)に変換
        const utcOffset = 9 * 60; // JSTはUTC+9時間
        const localDate = new Date(date.getTime() + utcOffset * 60 * 1000);
        return localDate.toISOString().split('T')[0];
    }

    function filterDataRecords() {
        const selectedMemberId = document.getElementById('member_id').value;
        const attendanceRows = document.querySelectorAll('#attendanceTableBody tr');

        attendanceRows.forEach(row => {
            const memberId = row.getAttribute('data-member-id');

            if ((selectedMemberId === "" || selectedMemberId == memberId) &&
                (window.startDate === undefined || window.endDate === undefined ||
                 (row.cells[0].innerText >= window.startDate && row.cells[0].innerText <= window.endDate))) {
                row.style.display = ''; // 表示
            } else {
                row.style.display = 'none'; // 非表示
            }
        });
        document.getElementById('export_member_id').value = selectedMemberId;
    }

    function validateExportForm() {
        const startDate = document.querySelector('#date_range')._flatpickr.selectedDates[0];
        const endDate = document.querySelector('#date_range')._flatpickr.selectedDates[1];

        if (!startDate || !endDate) {
            alert('日付範囲を選択してください。');
            return false;
        }

        return true;
    }
</script>



</x-app-layout>

