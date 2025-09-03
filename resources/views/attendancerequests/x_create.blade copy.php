<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('勤怠申請フォーム') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="
                bg-white 
                dark:bg-gray-800 
                shadow-sm 
                sm:rounded-lg 
                p-6 
                text-gray-900 
                dark:text-gray-100 
                w-full 
                max-w-xl
                ">
            <!-- ここにフォームや内容 -->
                <!-- アラート -->
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500 text-white p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('attendancerequests.store') }}">
                    @csrf

                    <!-- メンバー -->
                    <div class="mb-4">
                        <label for="member_id" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">メンバー</label>
                        <select name="member_id" id="member_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">選択してください</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 出勤日 -->
                    <div class="mb-4">
                        <label for="attendance_date" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">出勤日</label>
                        <input type="date" name="attendance_date" id="attendance_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('attendance_date')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 出勤時間 -->
                    <div class="mb-4">
                        <label for="clock_in" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">出勤時間</label>
                        <input type="time" name="clock_in" id="clock_in" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('clock_in')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 退勤時間 -->
                    <div class="mb-4">
                        <label for="clock_out" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">退勤時間</label>
                        <input type="time" name="clock_out" id="clock_out" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('clock_out')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 休憩分数 -->
                    <div class="mb-4">
                        <label for="break_minutes" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">休憩分数</label>
                        <input type="number" name="break_minutes" id="break_minutes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('break_minutes')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 備考 -->
                    <div class="mb-4">
                        <label for="remarks" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">備考</label>
                        <textarea name="remarks" id="remarks" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        @error('remarks')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 送信ボタン -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            申請する
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
