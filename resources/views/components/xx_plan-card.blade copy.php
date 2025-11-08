@props(['plans' => [],
        'user' => null,
])

<div class="flex flex-wrap gap-6 justify-center">
    @foreach($plans as $plan)
        <div class="p-6 {{ $plan['bg'] }} shadow-md rounded-3xl hover:scale-[1.02] transform transition-transform w-80 flex flex-col justify-between">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold {{ $plan['text'] }} mb-2">{{ $plan['name'] }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $plan['price'] }} / 月</p>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $plan['limit'] }} 名の名簿登録が可能。</p>
                {{-- ⭐ use_trial が false の場合のみトライアル日数表示 --}}
                @if($user && !$user->use_trial && !empty($plan['trial_days']))
                    <p class="text-green-600 dark:text-green-400 mb-2">
                        {{ $plan['trial_days'] }}日間無料トライアル
                    </p>
                @endif
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

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config("stripe.key") }}');

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
            window.location.href = result.redirect;
        } else if(result.id){
            stripe.redirectToCheckout({ sessionId: result.id });
        }
    }
</script>
