<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SafeTimeCard プラン一覧') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- コントローラから渡された $plans を渡す --}}
            <!-- <x-plan-card :plans="$plans" /> -->
             <x-plan-card :plans="$plans" :user="$user" :is-canceled="$isCanceled" />
            <!-- <x-plan-card :plans="$plans" :user="auth()->user()"/> -->
        </div>
    </div>

    <!-- {{-- モーダルを追加 --}}
    <x-plan-change-modal /> -->
</x-app-layout>
