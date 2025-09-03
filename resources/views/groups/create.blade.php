<!-- resources/views/groups/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('一般アカウント追加') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                ">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                            追加したい一般アカウントのメールアドレスを記入し、登録ボタンを押してください
                        </label>
                        <input type="email" name="email" id="email"
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
        </div>
    </div>
</x-app-layout>
