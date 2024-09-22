<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('休憩登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        現在時間: <span id="server-time">{{ \Carbon\Carbon::now()->toIso8601String() }}</span>
                    </h3>

                    <!-- アラートメッセージの表示 -->
                    @if (session('error'))
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('breaksessions.store') }}">
                        <input type="hidden" name="break_in" value="{{ \Carbon\Carbon::now()->toIso8601String() }}">
                        <input type="hidden" name="break_out" value="{{ \Carbon\Carbon::now()->toIso8601String() }}">
                        <div>
                            <select name="member_id" id="member_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @csrf
                        <div class="mb-4">
                            @error('breaksession')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" name="action" value="break_in" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">休憩開始</button>
                        <button type="submit" name="action" value="break_out" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">休憩終了</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // サーバー時間を取得して初期化
        const serverTimeElement = document.getElementById('server-time');
        let serverTime = new Date("{{ \Carbon\Carbon::now()->toIso8601String() }}");

        function updateServerTime() {
            // 1秒ずつサーバー時間を更新
            serverTime.setSeconds(serverTime.getSeconds() + 1);
            serverTimeElement.textContent = serverTime.toLocaleString("ja-JP", { timeZone: "Asia/Tokyo" });
        }

        // 1秒ごとに時間を更新
        setInterval(updateServerTime, 1000);
    </script>
</x-app-layout>
