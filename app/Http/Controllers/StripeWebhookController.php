<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook invalid payload');
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook invalid signature');
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                $user = User::where('stripe_customer_id', $subscription->customer)->first();
                if ($user) {
                    $user->stripe_status = $subscription->status;
                    $user->stripe_canceled_at = $subscription->cancel_at
                        ? date('Y-m-d H:i:s', $subscription->cancel_at)
                        : null;
                    $user->save();
                }
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $user = User::where('stripe_customer_id', $subscription->customer)->first();
                if ($user) {
                    $user->stripe_status = 'canceled';
                    $user->stripe_canceled_at = now();
                    $user->save();
                }
                break;
        }

        Log::info('✅ Stripe webhook received: ' . $event->type);

        return response('ok', 200);
    }
}
