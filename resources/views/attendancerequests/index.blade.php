<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            事後申請一覧
        </h2>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div id="flash-message" class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                const msg = document.getElementById('flash-message');
                                if (msg) {
                                    msg.style.transition = "opacity 0.5s";
                                    msg.style.opacity = 0;
                                    setTimeout(() => msg.remove(), 500);
                                }
                            }, 3000);
                        </script>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <!-- <div class="mb-6 flex flex-wrap items-end gap-4"> -->
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
                    </div>
                
            <!-- 横スクロール対応のラッパー -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">名前</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">日付</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">出勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">退勤</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">休憩</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">備考</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">状態</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">操作</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceRequestTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                {{-- 初期は空、JSで描画 --}}
                                @include('attendancerequests._table_rows')
                            </tbody>
                        </table>
                        <div id="paginationContainer" class="mt-4">
                            {{-- 初期は空、JSで描画 --}}
                            @include('attendancerequests._pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const memberSelect = document.getElementById('member_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const tableBody = document.getElementById('attendanceRequestTableBody'); // ← typo修正
        const paginationContainer = document.getElementById('paginationContainer');

        function fetchAttendanceRequests(url = null) {
            const params = new URLSearchParams();
            if(memberSelect.value) params.append('member_id', memberSelect.value);
            if(startDateInput.value) params.append('start_date', startDateInput.value);
            if(endDateInput.value) params.append('end_date', endDateInput.value);

            url = url || `/attendancerequests/filter?${params.toString()}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = data.rows;
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationLinks();
                })
                .catch(err => console.error(err));
        }

        function attachPaginationLinks() {
            document.querySelectorAll('#paginationContainer a').forEach(link => {
                link.addEventListener('click', function(e) { // ← typo修正 linl → link
                    e.preventDefault();
                    fetchAttendanceRequests(this.href);
                });
            });
        }

        // 初期ロード時にAjaxでデータ取得
        document.addEventListener('DOMContentLoaded', function() {
            fetchAttendanceRequests();
        });

        // セレクターや日付変更でAjax更新
        memberSelect.addEventListener('change', () => fetchAttendanceRequests());
        startDateInput.addEventListener('change', () => fetchAttendanceRequests());
        endDateInput.addEventListener('change', () => fetchAttendanceRequests());
    </script>

</x-app-layout>
