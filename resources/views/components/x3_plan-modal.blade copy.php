{{-- resources/views/components/plan-modal.blade.php --}}
@php
    // config から取得
    $plans = $plans ?? config('stripe.plans_list');
@endphp

@if(auth()->check() && auth()->user()->role === 'admin' && empty(auth()->user()->stripe_plan))
    <div class="fixed inset-0 bg-black bg-opacity-70 z-[9998]"></div>

    <div class="fixed inset-0 flex items-center justify-center z-[9999]">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl w-full max-w-3xl text-center relative">
            <h2 class="text-2xl font-bold mb-6">プランを選択してください</h2>

            {{-- $plans を plan-card に渡す --}}
            <x-plan-card :plans="$plans" :user="auth()->user()"/>
        </div>
    </div>

    
    {{-- 背景スクロール禁止 --}}
    <script>
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', e => { if(e.key === "Escape") e.preventDefault(); });
    </script>
@endif
