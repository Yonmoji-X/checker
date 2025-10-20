{{-- resources/views/components/plan-modal.blade.php --}}

{{-- body直下 --}}
@if(auth()->check() && auth()->user()->role === 'admin' && empty(auth()->user()->stripe_plan))
    {{-- 背景 --}}
    <div class="fixed inset-0 bg-black bg-opacity-70 z-[9998]"></div>

    {{-- モーダル本体 --}}
    <div class="fixed inset-0 flex items-center justify-center z-[9999]">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl w-96 text-center relative">
            <h2 class="text-2xl font-bold mb-6">プランを選択してください</h2>
            <div class="flex flex-col gap-4">
                <button onclick="selectPlan('free')" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-xl">フリープラン</button>
                <button onclick="selectPlan('standard')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-xl">スタンダードプラン</button>
            </div>
        </div>
    </div>
@endif


<script>
    // 背景スクロール禁止
    document.body.style.overflow = 'hidden';

    // クリックやESCキーも無効化
    document.addEventListener('keydown', e => { if(e.key === "Escape") e.preventDefault(); });

    async function selectPlan(plan) {
        const res = await fetch('{{ route("checkout.session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ plan })
        });
        const result = await res.json();

        if(result.redirect){
            window.location.href = result.redirect; // 無料プラン
        } else if(result.id){
            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
            stripe.redirectToCheckout({ sessionId: result.id });
        }
    }
</script>
