<x-policy-layout title="サブスクリプション解約">
    <div class="max-w-4xl mx-auto p-6 space-y-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">サブスクリプション解約</h1>

        <p>現在のプラン: <strong>{{ $planName }}</strong></p>
        <p>解約を行うと、現在の請求期間終了時にサービス利用が停止されます。</p>

        <form action="{{ route('checkout.unsubscribe.post') }}" method="POST">
            @csrf
            <button type="submit" 
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800 transition">
                解約する
            </button>
        </form>
    </div>
</x-policy-layout>
