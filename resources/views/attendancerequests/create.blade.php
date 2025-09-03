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
                <!-- アラート -->
                @if(session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('attendancerequests.store') }}">
                    @csrf

                    <!-- メンバー & 出勤日 横並び -->
                    <div class="mb-5 flex gap-4">
                        <div class="flex-1">
                            <label for="member_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">メンバー</label>
                            <select name="member_id" id="member_id" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                                <option value="">選択してください</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label for="attendance_date" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">出勤日</label>
                            <input type="date" name="attendance_date" id="attendance_date" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            @error('attendance_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 出勤時間 & 退勤時間 横並び -->
                    <div class="mb-5 flex gap-4">
                        <div class="flex-1">
                            <label for="clock_in" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">出勤時間</label>
                            <input type="time" name="clock_in" id="clock_in" value="{{ old('clock_in', '08:00') }}"  class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            @error('clock_in')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label for="clock_out" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">退勤時間</label>
                            <input type="time" name="clock_out" id="clock_out" value="{{ old('clock_out', '17:00') }}"  class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            @error('clock_out')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 休憩分数（幅を狭く） -->
                    <div class="mb-5 w-1/3">
                        <label for="break_minutes" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">休憩分数</label>
                        <input type="number" name="break_minutes" id="break_minutes" value="60" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        @error('break_minutes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 備考 -->
                    <div class="mb-5">
                        <label for="remarks" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">備考</label>
                        <textarea name="remarks" id="remarks" rows="4" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                        @error('remarks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 送信ボタン -->
                    <div class="mt-6 flex justify-center">
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200">
                            申請する
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
