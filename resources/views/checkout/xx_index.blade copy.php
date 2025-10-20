<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SafeTimeCard プラン一覧') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-wrap gap-6 justify-center">
                
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

                @foreach($plans as $plan)
                <div class="p-6 {{ $plan['bg'] }} shadow-md rounded-3xl hover:scale-[1.02] transform transition-transform w-80 flex flex-col justify-between">
                    
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold {{ $plan['text'] }} mb-2">{{ $plan['name'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $plan['price'] }} / 月</p>
                    </div>

                    <button 
                        class="font-semibold py-2 px-4 rounded-xl transition-colors {{ $plan['button'] }}"
                        onclick="createCheckout('{{ $plan['key'] }}')"
                    >
                        購入する
                    </button>
                </div>
                @endforeach

            </div>

        </div>
    </div>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        async function createCheckout(priceIdOrPlan) {
            const response = await fetch('/create-checkout-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ plan: priceIdOrPlan })
            });

            const result = await response.json();

            if (result.redirect) {
                window.location.href = result.redirect;
                return;
            }

            await stripe.redirectToCheckout({ sessionId: result.id });
        }
    </script>
</x-app-layout>
