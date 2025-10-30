<!-- resources/views/groups/create.blade.php -->

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('利用者アカウント追加') }}
        </h2>
    </x-slot>
    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 30000)" 
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 transition-all duration-500"
        >
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 30000)" 
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 transition-all duration-500"
        >
            {{ session('success') }}
        </div>
    @endif


    <div class="py-12">
        <div class="flex flex-col justify-center max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- 登録フォーム -->
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
                mx-auto
                ">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                            利用者アカウントのメールアドレスを記入し、登録ボタンを押してください
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            placeholder="例：example@email.com"
                            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600
                                bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            required
                        >

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-center">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200">
                            登録
                        </button>
                    </div>
                </form>
            </div>

            <!-- 解説部分 -->
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
                mx-auto
            ">
                <h3 class="text-lg font-semibold mb-4">ご利用方法</h3>

                <div class="space-y-3 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                    <p>
                        登録済みの利用者アカウントをグループに追加するには、上記のフォームにそのメールアドレスを入力してください。
                    </p>
                    <p>
                        現在使用されているアカウントは <strong>管理者アカウント</strong> です。
                    </p>
                    <p>
                        利用者アカウントは、管理者アカウントに紐づけ可能なサブアカウントであり、
                        設定の変更などの管理操作はできません。
                    </p>
                    <p>
                        まずは、<strong>利用者アカウント（無料）</strong>を作成し、
                        ここから追加することで紐づけ可能になります。
                    </p>
                </div>

                <h3 class="text-lg font-semibold mt-8 mb-3">利用者アカウントの作成方法</h3>
                <ul class="list-decimal list-inside text-gray-600 dark:text-gray-300 text-sm space-y-2">
                    <li>ログアウトします。</li>
                    <li>新規登録ページにアクセスします。</li>
                    <li>管理アカウントとは別のメールアドレスとパスワードを入力し、権限欄で「利用者アカウント」を選択して登録します。</li>
                    <li>登録完了後、再度管理者アカウントでログインし、上記フォームから利用者アカウントのメールアドレスを追加します。</li>
                </ul>

                <p class="text-gray-600 dark:text-gray-300 text-sm mt-4">
                    ※管理者以外の社員は利用者アカウントを使用してください。
                </p>
            </div>

        </div>

    </div>
</x-app-layout>
