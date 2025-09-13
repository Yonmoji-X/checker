<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('チェック一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full max-w-xl p-6">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 mb-6">
                    <div>
                        <label for="member_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバーの種類</label>
                        <select name="member_status" id="member_status" onchange="fetchRecords()"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700
                                    dark:border-gray-600 dark:text-gray-300 rounded-lg shadow-sm focus:outline-none
                                    focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm">
                            <option value="0" @if($templateMemberStatus == 0) selected @endif>従業員</option>
                            <option value="1" @if($templateMemberStatus == 1) selected @endif>管理者</option>
                        </select>
                    </div>
                    <div>
                        <label for="clock_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
                        <select name="clock_status" id="clock_status" onchange="fetchRecords()"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700
                                    dark:border-gray-600 dark:text-gray-300 rounded-lg shadow-sm focus:outline-none
                                    focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm">
                            <option value="1" @if($templateClockStatus == 1) selected @endif>出勤時</option>
                            <option value="0" @if($templateClockStatus == 0) selected @endif>退勤時</option>
                        </select>
                    </div>
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                        <select name="member_id" id="member_id" onchange="fetchRecords()"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700
                                    dark:border-gray-600 dark:text-gray-300 rounded-lg shadow-sm focus:outline-none
                                    focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm">
                            <option value="">全員</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">開始日</label>
                        <input type="date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md" onchange="fetchRecords()">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">終了日</label>
                        <input type="date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md" onchange="fetchRecords()">
                    </div>                    
                </div>

                <div id="record_post_body">
                    @include('records._table_rows', ['recordsGroups' => $paginatedGroups, 'templates' => $templates])
                </div>

                <div id="record_pagination_container">
                    @include('records._pagination', ['headIds' => $paginatedGroups])
                </div>
            </div>
        </div>
    </div>

    <script>
        const memberSelect = document.getElementById('member_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const memberStatusInput = document.getElementById('member_status');
        const clockStatusInput = document.getElementById('clock_status');
        const postBody = document.getElementById('record_post_body');
        const paginationContainer = document.getElementById('record_pagination_container');

        const filterUrl = "{{ route('records.filter') }}";

        function fetchRecords(url = null) {
            const params = new URLSearchParams();
            if(memberSelect.value) params.append('member_id', memberSelect.value);
            if(startDateInput.value) params.append('start_date', startDateInput.value);
            if(endDateInput.value) params.append('end_date', endDateInput.value);
            if(memberStatusInput.value) params.append('member_status', memberStatusInput.value);
            if(clockStatusInput.value) params.append('clock_status', clockStatusInput.value);

            url = url || filterUrl + '?' + params.toString();

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                postBody.innerHTML = data.rows;
                paginationContainer.innerHTML = data.pagination;
                attachPaginationLinks();
            })
            .catch(err => console.error(err));
        }

        function attachPaginationLinks() {
            document.querySelectorAll('#record_pagination_container a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchRecords(this.href);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchRecords();
        });
    </script>

</x-app-layout>
