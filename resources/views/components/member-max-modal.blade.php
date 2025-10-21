{{-- resources/views/components/member-max-modal.blade.php --}}

@props([
    'message' => null,
    'changePlanRoute' => route('checkout') // プラン変更ページのルート
])

@if(session('error') || !empty($message))
    @php
        $displayMessage = session('error') ?? $message ?? "このプランでは名簿の上限に達しています。";
    @endphp

    {{-- 背景オーバーレイ --}}
    <div class="fixed inset-0 bg-black bg-opacity-70 z-[9998]"></div>

    {{-- モーダル本体 --}}
    <div class="fixed inset-0 flex items-center justify-center z-[9999] px-4">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl w-full max-w-3xl text-center relative shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-red-600 dark:text-red-400">名簿の上限に達しました</h2>
            
            <p class="mb-6 text-gray-700 dark:text-gray-300">
                {{ $displayMessage }}
            </p>

            <a href="{{ $changePlanRoute }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
               プランを変更する
            </a>
        </div>
    </div>

    {{-- 背景スクロール禁止 --}}
    <script>
        document.body.style.overflow = 'hidden';
        // Escで閉じられないようにする（誤操作防止）
        document.addEventListener('keydown', e => { if(e.key === "Escape") e.preventDefault(); });
    </script>
@endif
