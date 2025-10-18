<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false }" class="z-50">
    <!-- 上部バー（タブレット以下で表示） -->
    <div class="md:hidden flex items-center justify-between bg-gray-800 text-white p-4">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="h-8 w-8 rounded-full" />
            <span class="font-semibold text-lg">AC-Sync</span>
        </div>
        <button @click="open = !open" class="focus:outline-none">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- サイドバー（PC表示 or モバイル展開時） -->
    <aside
        :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
        class="fixed md:translate-x-0 transition-transform duration-200 ease-in-out
               top-0 left-0 w-64 h-full bg-white dark:bg-gray-900 border-r
               border-gray-200 dark:border-gray-700 flex flex-col"
    >
        <!-- ロゴ -->
        <div class="hidden md:flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="h-9 w-9 rounded-full" />
            </a>
        </div>

        <!-- メニュー -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
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
                    {{ __('勤怠管理') }}
                </x-nav-link>
                <x-nav-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
                    {{ __('休憩データ') }}
                </x-nav-link>
            </div>

            <!-- 申請 -->
            <div>
                <p class="text-xs text-gray-400 uppercase mt-4 mb-1">申請</p>
                <x-nav-link :href="route('attendancerequests.create')" :active="request()->routeIs('attendancerequests.create')">
                    {{ __('事後申請') }}
                </x-nav-link>
                @if (auth()->user()->role === 'admin')
                    <x-nav-link :href="route('attendancerequests.index')" :active="request()->routeIs('attendancerequests.index')">
                        {{ __('申請一覧') }}
                    </x-nav-link>
                @endif
            </div>

            <!-- チェック一覧 -->
            <x-nav-link :href="route('records.index')" :active="request()->routeIs('records.index')">
                {{ __('チェック一覧') }}
            </x-nav-link>

            <!-- 管理者メニュー -->
            @if (auth()->user()->role === 'admin')
                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">アイテム</p>
                    <x-nav-link :href="route('templates.create')" :active="request()->routeIs('templates.create')">
                        {{ __('アイテム作成') }}
                    </x-nav-link>
                    <x-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.index')">
                        {{ __('アイテム一覧') }}
                    </x-nav-link>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">名簿</p>
                    <x-nav-link :href="route('members.create')" :active="request()->routeIs('members.create')">
                        {{ __('名簿登録') }}
                    </x-nav-link>
                    <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                        {{ __('名簿一覧') }}
                    </x-nav-link>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase mt-4 mb-1">管理</p>
                    <x-nav-link :href="route('groups.create')" :active="request()->routeIs('groups.create')">
                        {{ __('管理アカウント登録') }}
                    </x-nav-link>
                    <x-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.index')">
                        {{ __('管理アカウント一覧') }}
                    </x-nav-link>
                </div>
            @endif

            <!-- Checkout -->
            <div>
                <p class="text-xs text-gray-400 uppercase mt-4 mb-1">決済</p>
                <x-nav-link :href="route('checkout')" :active="request()->routeIs('checkout')">
                    {{ __('Checkout（決済）') }}
                </x-nav-link>
            </div>
        </nav>

        <!-- ユーザー情報とログアウト -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-700 dark:text-gray-300 mb-2 truncate">
                <div>{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                {{ __('プロフィール') }}
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mt-2 text-sm text-red-500 hover:text-red-700 w-full text-left">
                    {{ __('ログアウト') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- 背景クリックで閉じる -->
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black bg-opacity-40 md:hidden z-40"
    ></div>
</nav>
