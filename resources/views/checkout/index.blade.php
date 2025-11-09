<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SafeTimeCard プラン一覧') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen">
        <div class="py-6 flex-grow">
            <div class="max-w-7xl mx-auto sm:px-6">
            <!-- <div class="bg-white shadow p-6 rounded-none"> -->
                <x-plan-card :plans="$plans" :user="$user" :is-canceled="$isCanceled" />
            </div>
        </div>
    </div>
</x-app-layout>
