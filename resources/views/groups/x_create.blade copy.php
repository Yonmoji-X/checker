<!-- resources/views/groups/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('一般アカウント追加') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <form method="POST" action="{{ route('groups.store') }}">
                @csrf
                <div class="mb-4">
                <label for="group" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">追加したい一般アカウントのメールアドレスを記入し、登録ボタンを押してください</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('email')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">登録</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>



<!-- <!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
</head>
<body>
    <h1>Add User</h1>
    <form action="{{ route('groups.store') }}" method="POST">
        @csrf
        <label for="email">User Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Add User</button>
    </form>
</body>
</html> -->
