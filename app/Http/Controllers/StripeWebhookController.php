<?php
// ★このファイルは実質使っていないが、Stripe Webhookの受け口として残しておく
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
                    // ステータス更新
                    $user->stripe_status = $subscription->status;

                    // キャンセル予定がある場合
                    if ($subscription->cancel_at_period_end) {
                        // Stripeダッシュボードで表示される「キャンセル日」は通常 current_period_end
                        $user->stripe_canceled_at = date('Y-m-d H:i:s', $subscription->current_period_end);
                    } elseif ($subscription->cancel_at) {
                        // 明示的に cancel_at がある場合
                        $user->stripe_canceled_at = date('Y-m-d H:i:s', $subscription->cancel_at);
                    } else {
                        // キャンセル予定なし
                        $user->stripe_canceled_at = null;
                    }

                    $user->save();
                    Log::info("User {$user->id} subscription updated: status={$user->stripe_status}, canceled_at={$user->stripe_canceled_at}");
                }
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $user = User::where('stripe_customer_id', $subscription->customer)->first();
                if ($user) {
                    $user->stripe_status = 'canceled';
                    $user->stripe_canceled_at = now();
                    $user->save();
                    Log::info("User {$user->id} subscription canceled immediately.");
                }
                break;
        }

        return response('ok', 200);
    }
}
