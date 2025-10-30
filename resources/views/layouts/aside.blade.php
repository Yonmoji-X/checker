<nav class="z-50" x-data="{ open: false }">
    <!-- ✅ 上部バー（モバイルのみ） -->
    <div class="md:hidden flex items-center justify-between bg-white h-14 p-4 shadow fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="h-8 w-8 rounded-full" />
            <span class="font-semibold text-lg">{{ $header }}</span>
        </div>
    </div>

    <!-- ✅ オーバーレイ（暗幕） -->
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden transition-opacity duration-300"
        x-transition.opacity
    ></div>

    <!-- ✅ サイドバー本体 -->
    <aside
        :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
        class="fixed md:static top-0 left-0 w-64 h-full bg-gray-800 text-gray-100 flex flex-col justify-between
               transform transition-transform duration-300 ease-in-out z-50 md:translate-x-0 will-change-transform"
    >
        <!-- 上部タイトル -->
        <div>
            <div class="bg-gray-800 h-14 flex items-center justify-center text-center border-b border-gray-700 hidden md:flex">
                <span class="text-xl font-semibold">SafeTimeCard</span>
            </div>

            <!-- メニュー -->
            <nav class="mt-4 flex-1 overflow-y-auto px-4 space-y-2">
                <!-- 出退勤 -->
                <x-nav-link :href="route('records.create')" :active="request()->routeIs('records.create')" class="block">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 w-full rounded-lg transition">
                        {{ __('出退勤') }}
                    </button>
                </x-nav-link>

                <!-- 休憩 -->
                <x-nav-link :href="route('breaksessions.create')" :active="request()->routeIs('breaksessions.create')">
                    {{ __('休憩') }}
                </x-nav-link>

                <!-- 勤怠管理 -->
                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">勤怠管理</p>
                    <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
                        {{ __('勤怠データ') }}
                    </x-nav-link>
                    <x-nav-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
                        {{ __('休憩データ') }}
                    </x-nav-link>
                </div>

                <!-- 申請 -->
                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">申請</p>
                    <x-nav-link :href="route('attendancerequests.create')" :active="request()->routeIs('attendancerequests.create')">
                        {{ __('申請') }}
                    </x-nav-link>
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('attendancerequests.index')" :active="request()->routeIs('attendancerequests.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- チェック一覧 -->
                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">チェック項目</p>
                    <x-nav-link :href="route('records.index')" :active="request()->routeIs('records.index')">
                        {{ __('一覧') }}
                    </x-nav-link>
                </div>

                <!-- 管理者メニュー -->
                @if (auth()->user()->role === 'admin')
                    <div>
                        <p class="text-xs text-gray-400 uppercase mt-4 mb-1">アイテム</p>
                        <x-nav-link :href="route('templates.create')" :active="request()->routeIs('templates.create')">
                            {{ __('作成') }}
                        </x-nav-link>
                        <x-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase mt-4 mb-1">名簿</p>
                        <x-nav-link :href="route('members.create')" :active="request()->routeIs('members.create')">
                            {{ __('登録') }}
                        </x-nav-link>
                        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase mt-4 mb-1">管理アカウント</p>
                        <x-nav-link :href="route('groups.create')" :active="request()->routeIs('groups.create')">
                            {{ __('登録') }}
                        </x-nav-link>
                        <x-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>

                    <!-- 決済 -->
                    <div>
                        <p class="text-xs text-gray-400 uppercase mt-4 mb-1">決済</p>
                        <x-nav-link :href="route('checkout')" :active="request()->routeIs('checkout')">
                            {{ __('Checkout（決済）') }}
                        </x-nav-link>
                        <x-nav-link :href="route('checkout.plan')" :active="request()->routeIs('checkout.plan')">
                            {{ __('マイプラン') }}
                        </x-nav-link>
                    </div>
                @endif
            </nav>
        </div>

        <!-- 下部（ユーザー情報 + ログアウト） -->
        <div class="p-4 border-t border-gray-700 pb-6">
            <div class="text-sm text-gray-300 mb-2 truncate">
                <div>{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="block mb-2">
                {{ __('プロフィール') }}
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-md text-sm font-medium">
                    {{ __('ログアウト') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- ✅ モバイル専用フッター -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700 shadow md:hidden z-60">
        <div class="flex justify-around items-center py-2 pb-6">
            <!-- 出退勤 -->
            <a href="{{ route('records.create') }}"
               class="flex flex-col items-center text-gray-700 dark:text-gray-200 hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-xs font-semibold">出退勤</span>
            </a>

            <!-- 休憩 -->
            <a href="{{ route('breaksessions.create') }}"
               class="flex flex-col items-center text-gray-700 dark:text-gray-200 hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3" />
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                </svg>
                <span class="text-xs font-semibold">休憩</span>
            </a>

            <!-- ハンバーガー -->
            <button @click="open = !open"
               class="flex flex-col items-center text-gray-700 dark:text-gray-200 hover:text-blue-500 transition">
                <template x-if="!open">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </template>
                <template x-if="open">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </template>
                <span class="text-xs font-semibold">メニュー</span>
            </button>
        </div>
    </div>
</nav>
