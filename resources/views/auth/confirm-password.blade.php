<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('この操作を行うには安全な認証が必要です。続行する前にパスワードを確認してください。') }}
    </div>
    <div class="mb-4 text-sm text-red-600 dark:text-red-400">
        {{ __('パスワード入力後もう一度') }}
        <span class="font-extrabold text-red-700 text-lg bg-yellow-100 px-1 rounded">
            {{ __('「２段階認証を有効化」') }}
        </span>
        {{ __('ボタンを押してください。') }}
    </div>


    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('パスワード')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('確認') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
