<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SafeTimeCard プラン一覧') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-plan-card />
        </div>
    </div>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        async function createCheckout(planKey) {
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
</x-app-layout>
