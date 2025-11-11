<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">二段階認証の有効化</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p>下記のQRコードを認証アプリで読み取ったら、Confirmボタンを押してください。</p>
                
                <div class="my-4">
                    {!! $qrCode !!} {{-- Fortify の QR コード --}}
                </div>

                <form method="POST" action="{{ route('two-factor.confirm.post') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
