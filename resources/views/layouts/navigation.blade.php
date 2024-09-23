<!-- resources/views/layouts/navigation.blade.php -->

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
        <div class="flex">
            <!-- Logo -->
            <!-- <div class="shrin-0 flex items-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
            </div> -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="block h-9 w-auto rounded-full" />
                </a>
            </div>



            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('ダッシュボード') }}
            </x-nav-link>
            <!-- 🔽 2項目追加 -->
            @if (auth()->user()->role === 'admin')
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('アイテム') }}
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('templates.create')" :active="request()->routeIs('templates.create')">
                        {{ __('アイテム作成') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('templates.index')" :active="request()->routeIs('templates.index')">
                        {{ __('アイテム一覧') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
        <div class="hidden sm:flex sm:items-center sm:ms-6">

            <!-- 名簿関連のプルダウンメニュー -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('名簿') }}
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('members.create')" :active="request()->routeIs('members.create')">
                        {{ __('名簿登録') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                        {{ __('名簿') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>

            <!-- 管理関係のプルダウンメニュー -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('管理関係') }}
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('groups.create')" :active="request()->routeIs('groups.create')">
                        {{ __('管理アカウント登録') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('groups.index')" :active="request()->routeIs('groups.index')">
                        {{ __('管理アカウント') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>

        </div>

            @endif
<!-- ==================================================== -->

            <!-- 名簿関連のプルダウンメニュー -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('勤怠管理') }}
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
                        {{ __('勤怠管理') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
                        {{ __('休憩データ') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
            </div>
<!-- ==================================================== -->
            <!-- <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
                    {{ __('勤怠管理') }}
            </x-nav-link> -->
            <!-- <x-nav-link :href="route('attendances.create')" :active="request()->routeIs('attendances.create')">
                    {{ __('出退勤(仮)') }}
            </x-nav-link> -->
            <x-nav-link :href="route('records.index')" :active="request()->routeIs('records.index')">
                    {{ __('チェック一覧') }}
            </x-nav-link>
            <!-- <x-nav-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
                    {{ __('休憩一覧（仮）') }}
            </x-nav-link> -->
            <x-nav-link :href="route('breaksessions.create')" :active="request()->routeIs('breaksessions.create')">
                    {{ __('休憩') }}
                    <!-- <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">{{ __('休憩') }}</button> -->
            </x-nav-link>
            <x-nav-link :href="route('records.create')" :active="request()->routeIs('records.create')">
                <!-- {{ __('出退勤') }} -->
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">
                    {{ __('出退勤') }}
                </button>
            </x-nav-link>

            </div>
        </div>

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>{{ Auth::user()->name }}</div>

                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                {{ __('プロフィール') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                    {{ __('ログアウト') }}
                </x-dropdown-link>
                </form>
            </x-slot>
            </x-dropdown>
        </div>

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('ダッシュボード') }}
        </x-responsive-nav-link>
        <!-- 🔽 2項目追加 -->
        @if (auth()->user()->role === 'admin')
        <x-responsive-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.index')">
            {{ __('アイテム一覧') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('templates.create')" :active="request()->routeIs('templates.create')">
            {{ __('アイテム作成') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
            {{ __('名簿') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('members.create')" :active="request()->routeIs('members.create')">
            {{ __('名簿登録') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
            {{ __('管理アカウント') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('members.create')" :active="request()->routeIs('members.create')">
            {{ __('アカウント管理') }}
        </x-responsive-nav-link>
        @endif
        <x-responsive-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
            {{ __('勤怠管理') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('breaksessions.index')" :active="request()->routeIs('breaksessions.index')">
            {{ __('休憩データ') }}
        </x-responsive-nav-link>
        <!-- <x-responsive-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.index')">
            {{ __('出退勤（仮）') }}
        </x-responsive-nav-link> -->
        <x-responsive-nav-link :href="route('records.index')" :active="request()->routeIs('records.index')">
            {{ __('チェック一覧') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('breaksessions.create')" :active="request()->routeIs('breaksessions.create')">
            {{ __('休憩') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('records.create')" :active="request()->routeIs('records.create')">
            {{ __('チェック') }}
        </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
        <div class="px-4">
            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')">
            {{ __('Profile') }}
            </x-responsive-nav-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
            </form>
        </div>
        </div>
    </div>
</nav>
