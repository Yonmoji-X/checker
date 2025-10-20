@php
    $plans = [
        [
            'name' => 'フリープラン',
            'price' => '¥0',
            'key' => 'free',
            'price_id' => config('stripe.plans.free'),
            'bg' => 'bg-green-50 dark:bg-green-900',
            'text' => 'text-green-700 dark:text-green-300',
            'button' => 'bg-green-600 hover:bg-green-700 text-white',
        ],
        [
            'name' => 'スタンダードプラン',
            'price' => '¥1,980',
            'key' => 'standard',
            'price_id' => config('stripe.plans.standard'),
            'bg' => 'bg-blue-50 dark:bg-blue-900',
            'text' => 'text-blue-700 dark:text-blue-300',
            'button' => 'bg-blue-600 hover:bg-blue-700 text-white',
        ],
    ];
@endphp

<div class="flex flex-wrap gap-6 justify-center">
    @foreach($plans as $plan)
        <div class="p-6 {{ $plan['bg'] }} shadow-md rounded-3xl hover:scale-[1.02] transform transition-transform w-80 flex flex-col justify-between">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold {{ $plan['text'] }} mb-2">{{ $plan['name'] }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $plan['price'] }} / 月</p>
            </div>
            <button 
                class="font-semibold py-2 px-4 rounded-xl transition-colors {{ $plan['button'] }}"
                onclick="selectPlan('{{ $plan['key'] }}')"
            >
                購入する
            </button>
        </div>
    @endforeach
</div>

<!-- Stripe JS + 購入処理 -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');

    async function selectPlan(planKey) {
        const res = await fetch('{{ route("checkout.session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ plan: planKey })
        });

        const result = await res.json();

        if(result.redirect){
            window.location.href = result.redirect; // 無料プラン
        } else if(result.id){
            stripe.redirectToCheckout({ sessionId: result.id });
        }
    }
</script>
