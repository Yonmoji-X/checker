<nav class="z-50">
    <!-- ✅ 上部バー（モバイルのみ） -->
    <div class="md:hidden flex items-center justify-between bg-indigo-600 text-white h-14 p-4 shadow fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="h-8 w-8 rounded-full bg-white" />
            <span class="font-semibold text-lg">SafeTimeCard</span>
        </div>
        <button @click="open = !open" class="focus:outline-none p-2 rounded-md hover:bg-indigo-700 transition">
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

    <!-- ✅ サイドバー本体 -->
    <aside
        :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
        class="fixed md:static top-0 left-0 w-64 h-full bg-white text-gray-800 shadow-xl flex flex-col justify-between
               transform transition-transform duration-300 ease-in-out z-50 md:translate-x-0 border-r border-indigo-100"
    >
        <!-- 上部タイトル -->
        <div>
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white h-14 flex items-center justify-center text-center hidden md:flex rounded-br-2xl">
                <span class="text-xl font-semibold">SafeTimeCard</span>
            </div>

            <!-- メニュー -->
            <nav class="mt-4 flex-1 overflow-y-auto px-4 space-y-2">
                <!-- 出退勤 -->
                <x-nav-link :href="route('records.create')" :active="request()->routeIs('records.create')" class="block">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 w-full rounded-lg transition shadow-sm">
                        {{ __('出退勤') }}
                    </button>
                </x-nav-link>

                <x-nav-link :href="route('breaksessions.create')" :active="request()->routeIs('breaksessions.create')">
                    {{ __('休憩') }}
                </x-nav-link>

                <!-- 勤怠管理 -->
                <div>
                    <p class="text-xs text-gray-500 uppercase mt-4 mb-1">勤怠管理</p>
                    <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
                        {{ __('勤怠データ') }}
                    </x-nav-link>
                    <x-nav-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
                        {{ __('休憩データ') }}
                    </x-nav-link>
                </div>

                <!-- 申請 -->
                <div>
                    <p class="text-xs text-gray-500 uppercase mt-4 mb-1">申請</p>
                    <x-nav-link :href="route('attendancerequests.create')" :active="request()->routeIs('attendancerequests.create')">
                        {{ __('申請') }}
                    </x-nav-link>
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('attendancerequests.index')" :active="request()->routeIs('attendancerequests.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- チェック項目 -->
                <div>
                    <p class="text-xs text-gray-500 uppercase mt-4 mb-1">チェック項目</p>
                    <x-nav-link :href="route('records.index')" :active="request()->routeIs('records.index')">
                        {{ __('一覧') }}
                    </x-nav-link>
                </div>

                <!-- 管理者メニュー -->
                @if (auth()->user()->role === 'admin')
                    <div>
                        <p class="text-xs text-gray-500 uppercase mt-4 mb-1">アイテム</p>
                        <x-nav-link :href="route('templates.create')" :active="request()->routeIs('templates.create')">
                            {{ __('作成') }}
                        </x-nav-link>
                        <x-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase mt-4 mb-1">名簿</p>
                        <x-nav-link :href="route('members.create')" :active="request()->routeIs('members.create')">
                            {{ __('登録') }}
                        </x-nav-link>
                        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase mt-4 mb-1">管理アカウント</p>
                        <x-nav-link :href="route('groups.create')" :active="request()->routeIs('groups.create')">
                            {{ __('登録') }}
                        </x-nav-link>
                        <x-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.index')">
                            {{ __('一覧') }}
                        </x-nav-link>
                    </div>
                    
                    <!-- 決済 -->
                    <div>
                        <p class="text-xs text-gray-500 uppercase mt-4 mb-1">決済</p>
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
        <div class="p-4 border-t border-indigo-100 bg-indigo-50 rounded-tl-2xl">
            <div class="text-sm text-gray-700 mb-2 truncate">
                <div class="font-semibold">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="block mb-2 text-indigo-600 hover:text-indigo-800">
                {{ __('プロフィール') }}
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md text-sm font-medium transition">
                    {{ __('ログアウト') }}
                </button>
            </form>
        </div>
    </aside>
</nav>
