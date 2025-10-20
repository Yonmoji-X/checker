<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ホーム') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (auth()->user()->role === 'admin')
                        <h3 class="text-2xl font-bold">管理者アカウント</h3>
                        <p>全ての権限を持つアカウントです。</p>
                    @elseif (auth()->user()->role === 'user')
                        <h3 class="text-2xl font-bold">一般アカウント</h3><br>
                        <p>権限に一部制限があります。管理権限で操作したい場合は、管理アカウントでログインしてください。</p><br><br>

                        <h5 class="text-2xl font-bold">管理者情報</h5><br>
                        <div>
                            <ul>
                                @if ($admin)
                                    <li>管理者名：{{ $admin->name }} </li>
                                    <li>メール　：{{ $admin->email }}</li>
                                @else
                                    <li>管理者情報はありません</li>
                                @endif
                            </ul>
                        </div>

                    @else
                        <h3 class="text-2xl font-bold">ようこそ</h3>
                        <p></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
