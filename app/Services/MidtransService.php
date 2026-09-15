<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private string $serverKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->apiUrl = config('midtrans.api_url');
    }

    /**
     * Generate Snap token for an order.
     */
    public function createSnapToken(Order $order): string
    {
        $order->load('items.product');

        $itemDetails = [];
        $itemsTotal = 0;

        foreach ($order->items as $item) {
            $unitPrice = intval($item->subtotal / $item->quantity);
            $itemDetails[] = [
                'id' => 'PROD-' . $item->product_id,
                'price' => $unitPrice,
                'quantity' => $item->quantity,
                'name' => mb_substr($item->product->name . ' (' . ($item->options_label ?: 'Standard') . ')', 0, 50),
            ];
            $itemsTotal += $unitPrice * $item->quantity;
        }

        // Tax line item
        if ($order->tax_amount > 0) {
            $itemDetails[] = [
                'id' => 'TAX-PPN-11',
                'price' => $order->tax_amount,
                'quantity' => 1,
                'name' => 'PPN 11%',
            ];
        }

        // Service fee line item
        if ($order->service_fee > 0) {
            $itemDetails[] = [
                'id' => 'SVC-FEE',
                'price' => $order->service_fee,
                'quantity' => 1,
                'name' => 'Service Fee',
            ];
            $itemsTotal += $order->service_fee;
        }

        // Tax line item already added above
        $itemsTotal += $order->tax_amount;

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                // gross_amount MUST equal sum of item_details (price*qty)
                'gross_amount' => $itemsTotal,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'callbacks' => [
                'finish' => url('/orders/' . $order->uuid),
            ],
        ];

        Log::info('Midtrans Snap Request', [
            'order' => $order->order_number,
            'gross_amount' => $itemsTotal,
            'api_url' => $this->apiUrl,
        ]);

        $response = Http::acceptJson()
            ->withBasicAuth($this->serverKey, '')
            ->post($this->apiUrl, $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap Token Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order' => $order->order_number,
            ]);

            $errorBody = $response->json();
            $hint = $response->status() === 401
                ? 'Server Key tidak valid. Periksa MIDTRANS_SERVER_KEY di .env (ambil dari dashboard.sandbox.midtrans.com → Settings → Access Keys).'
                : ($errorBody['error_messages'][0] ?? $response->body());

            throw new \RuntimeException($hint);
        }

        $data = $response->json();

        return $data['token'];
    }

    /**
     * Verify Midtrans notification signature (SHA-512).
     * signature_key = SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Map Midtrans transaction status to our payment status.
     */
    public function resolvePaymentStatus(array $notification): string
    {
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? 'accept';

        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'failed',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            default => 'pending',
        };
    }
}
