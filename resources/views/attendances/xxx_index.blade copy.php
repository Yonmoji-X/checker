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

                    <!-- メンバー選択・日付範囲 -->
                <!-- メンバー選択・日付範囲・エクスポート -->
                <div class="mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                        <select id="member_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <option value="">全ての従業員</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">開始日</label>
                        <input type="date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">終了日</label>
                        <input type="date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                    </div>

                    <!-- エクスポートボタン -->
                    <div>
                        <form id="exportForm" action="{{ route('attendance.export') }}" method="POST">
                            @csrf
                            <input type="hidden" name="member_id" id="export_member_id" value="">
                            <input type="hidden" name="start_date" id="export_start_date" value="">
                            <input type="hidden" name="end_date" id="export_end_date" value="">
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow"
                                style="background-color:#217346; color:white;">
                                ダウンロード
                            </button>



                        </form>
                    </div>
                </div>


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
                            <tbody id="attendanceTableBody" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @include('attendances._table_rows', ['attendances' => $attendances])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JS for Fetch + Export -->
    <script>
        const memberSelect = document.getElementById('member_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const tableBody = document.getElementById('attendanceTableBody');

        const exportMemberInput = document.getElementById('export_member_id');
        const exportStartInput = document.getElementById('export_start_date');
        const exportEndInput = document.getElementById('export_end_date');

        function updateExportInputs() {
            exportMemberInput.value = memberSelect.value;
            exportStartInput.value = startDateInput.value;
            exportEndInput.value = endDateInput.value;
        }

        function fetchAttendances() {
            const memberId = memberSelect.value;
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            const params = new URLSearchParams();
            if(memberId) params.append('member_id', memberId);
            if(startDate) params.append('start_date', startDate);
            if(endDate) params.append('end_date', endDate);

            fetch(`/attendances/filter?${params.toString()}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    updateExportInputs(); // **ここでエクスポート hidden input を更新**
                })
                .catch(err => console.error(err));
        }

        memberSelect.addEventListener('change', fetchAttendances);
        startDateInput.addEventListener('change', fetchAttendances);
        endDateInput.addEventListener('change', fetchAttendances);

        // 初期値セット
        updateExportInputs();
    </script>

</x-app-layout>
