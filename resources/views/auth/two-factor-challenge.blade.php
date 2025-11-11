<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            二段階認証コードを入力してください。
        </div>

        <form method="POST" action="{{ url('/two-factor-challenge') }}">
            @csrf

            <div>
                <x-input-label for="code" value="認証コード" />
                <x-text-input id="code" type="text" name="code" class="mt-1 block w-full" autofocus />
            </div>

            <div class="mt-4">
                <x-input-label for="recovery_code" value="リカバリーコード（紛失時）" />
                <x-text-input id="recovery_code" type="text" name="recovery_code" class="mt-1 block w-full" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    ログイン
                </x-primary-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
