<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            勤怠申請編集
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="flex justify-center max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 w-full max-w-xl">
                
                <a href="{{ route('attendancerequests.index') }}" class="text-blue-500 underline mb-4 inline-block">
                    一覧に戻る
                </a>

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('attendancerequests.update', $requestData->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- メンバー -->
                    <div class="mb-4">
                        <label for="member_id" class="block font-medium text-sm text-gray-700">メンバー</label>
                        <select name="member_id" id="member_id" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @if($requestData->member_id == $member->id) selected @endif>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('member_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 出勤日 -->
                    <div class="mb-4">
                        <label for="attendance_date" class="block font-medium text-sm text-gray-700">出勤日</label>
                        <input type="date" name="attendance_date" id="attendance_date" value="{{ $requestData->attendance_date }}" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                        @error('attendance_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 出勤時間 -->
                    <div class="mb-4">
                        <label for="clock_in" class="block font-medium text-sm text-gray-700">出勤時間</label>
                        <input type="time" name="clock_in" id="clock_in" 
                            value="{{ $requestData->clock_in ? \Carbon\Carbon::parse($requestData->clock_in)->format('H:i') : '' }}" 
                            class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                        @error('clock_in') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 退勤時間 -->
                    <div class="mb-4">
                        <label for="clock_out" class="block font-medium text-sm text-gray-700">退勤時間</label>
                        <input type="time" name="clock_out" id="clock_out" 
                            value="{{ $requestData->clock_out ? \Carbon\Carbon::parse($requestData->clock_out)->format('H:i') : '' }}" 
                            class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                        @error('clock_out') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 休憩分数 -->
                    <div class="mb-4">
                        <label for="break_minutes" class="block font-medium text-sm text-gray-700">休憩分数</label>
                        <input type="number" name="break_minutes" id="break_minutes" value="{{ $requestData->break_minutes }}" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                        @error('break_minutes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 備考 -->
                    <div class="mb-4">
                        <label for="remarks" class="block font-medium text-sm text-gray-700">備考</label>
                        <textarea name="remarks" id="remarks" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">{{ $requestData->remarks }}</textarea>
                        @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- ボタン -->
                    <div class="flex justify-end gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow">
                            更新・承認
                        </button>
                        <a href="{{ route('attendancerequests.reject', $requestData->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md shadow">
                            却下
                        </a>
                        <a href="{{ route('attendancerequests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">
                            キャンセル
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
