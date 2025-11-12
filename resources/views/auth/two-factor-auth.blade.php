<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
            
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                    {{ __('二段階認証の設定') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('二段階認証を有効化・無効化できます。必ずPCで設定を行ってください。') }}
                </p>
            </div>

            {{-- 二段階認証フォームを埋め込む --}}
            @include('profile.partials.two-factor-authentication-form')

        </div>
    </div>
</x-guest-layout>
