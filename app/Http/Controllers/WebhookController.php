<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    /**
     * Handle Midtrans payment notification webhook.
     * POST /api/midtrans/webhook
     */
    public function handle(Request $request)
    {
        $notification = $request->all();

        Log::info('Midtrans Webhook Received', $notification);

        // 1. Verify signature
        if (!$this->midtrans->verifySignature($notification)) {
            Log::warning('Midtrans Webhook: Invalid signature', ['order_id' => $notification['order_id'] ?? 'unknown']);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderNumber = $notification['order_id'] ?? null;
        if (!$orderNumber) {
            return response()->json(['message' => 'Missing order_id'], 400);
        }

        // 2. Resolve payment status
        $newStatus = $this->midtrans->resolvePaymentStatus($notification);

        // 3. Update order with lock to prevent race conditions
        try {
            DB::transaction(function () use ($orderNumber, $newStatus, $notification) {
                $order = Order::where('order_number', $orderNumber)->lockForUpdate()->first();

                if (!$order) {
                    Log::warning('Midtrans Webhook: Order not found', ['order_number' => $orderNumber]);
                    return;
                }

                // Idempotency: don't process if already in final state
                if ($order->isFinal()) {
                    Log::info('Midtrans Webhook: Order already final', [
                        'order_number' => $orderNumber,
                        'current_status' => $order->payment_status,
                    ]);
                    return;
                }

                $order->update([
                    'payment_status' => $newStatus,
                    'payment_method' => $notification['payment_type'] ?? null,
                    'paid_at' => $newStatus === 'paid' ? now() : null,
                ]);

                Log::info('Midtrans Webhook: Order updated', [
                    'order_number' => $orderNumber,
                    'status' => $newStatus,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error', [
                'message' => $e->getMessage(),
                'order_number' => $orderNumber,
            ]);
            return response()->json(['message' => 'Internal error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }
}
