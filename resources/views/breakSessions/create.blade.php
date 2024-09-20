<!-- resources/views/breaksessions/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('休憩登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <form method="POST" action="{{ route('breaksessions.store') }}">
                @csrf
                <div class="mb-4">
                <label for="breaksession" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">breaksession</label>
                <input type="text" name="breaksession" id="breaksession" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('breaksession')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">breaksession</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
