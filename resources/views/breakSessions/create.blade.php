<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('休憩') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg w-full max-w-xl p-6">
                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    現在時間: <span id="server-time">{{ \Carbon\Carbon::now()->toIso8601String() }}</span>
                </h3>

                <!-- アラートメッセージ -->
                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('breaksessions.store') }}">
                    @csrf
                    <input type="hidden" name="break_in" value="{{ \Carbon\Carbon::now()->toIso8601String() }}">
                    <input type="hidden" name="break_out" value="{{ \Carbon\Carbon::now()->toIso8601String() }}">

                    <div class="mb-4">
                        <label for="member_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">メンバー</label>
                        <select name="member_id" id="member_id" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            @foreach ($members as $member)
                                @php
                                    // そのメンバーが現在休憩中かチェック
                                    $isCurrentlyOnBreak = $member->attendances()->latest('attendance_date')->first()?->breakSessions->contains(function($breakSession) {
                                        return $breakSession->break_in && is_null($breakSession->break_out);
                                    });
                                @endphp
                                <option value="{{ $member->id }}">
                                    {{ $member->name }}@if($isCurrentlyOnBreak) （休憩中）@endif
                                </option>
                            @endforeach
                        </select>

                        @error('breaksession')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-center gap-4">
                        <button type="submit" name="action" value="break_in" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200">
                            休憩開始
                        </button>
                        <button type="submit" name="action" value="break_out" 
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition-all duration-200">
                            休憩終了
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // サーバー時間を更新
        const serverTimeElement = document.getElementById('server-time');
        let serverTime = new Date("{{ \Carbon\Carbon::now()->toIso8601String() }}");

        function updateServerTime() {
            serverTime.setSeconds(serverTime.getSeconds() + 1);
            serverTimeElement.textContent = serverTime.toLocaleString("ja-JP", { timeZone: "Asia/Tokyo" });
        }

        setInterval(updateServerTime, 1000);
    </script>
</x-app-layout>
