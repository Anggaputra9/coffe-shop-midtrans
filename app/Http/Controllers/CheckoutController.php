<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    /**
     * Process checkout: validate, save order, generate snap token.
     */
    public function store(CheckoutRequest $request)
    {
        $validated = $request->validated();

        try {
            $order = DB::transaction(function () use ($validated) {
                // Calculate item totals
                $subtotal = 0;
                $itemsData = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $options = $item['options'] ?? [];
                    $unitPrice = $this->calculateUnitPrice($product->base_price, $options);
                    $qty = $item['quantity'];
                    $lineTotal = $unitPrice * $qty;
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'base_price' => $product->base_price,
                        'subtotal' => $lineTotal,
                        'selected_options' => $options,
                    ];
                }

                $taxAmount = (int) round($subtotal * 0.11);
                $serviceFee = 2000;
                $totalAmount = $subtotal + $taxAmount + $serviceFee;

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'order_type' => $validated['order_type'],
                    'table_or_notes' => $validated['table_or_notes'] ?? null,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'service_fee' => $serviceFee,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'pending',
                ]);

                foreach ($itemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                return $order;
            });

            // Generate Snap token
            $snapToken = $this->midtrans->createSnapToken($order);
            $order->update(['snap_token' => $snapToken]);

            // Remember email so the customer can see their purchase history
            session(['customer_email' => $order->customer_email]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_uuid' => $order->uuid,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout Error', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate unit price with modifier surcharges.
     */
    private function calculateUnitPrice(int $basePrice, array $options): int
    {
        $price = $basePrice;

        if (($options['size'] ?? 'regular') === 'large') {
            $price += 6000;
        }
        if (($options['bean'] ?? 'house_blend') === 'single_origin') {
            $price += 5000;
        }
        if (in_array($options['milk'] ?? 'dairy', ['oat_milk', 'almond_milk'])) {
            $price += 8000;
        }

        return $price;
    }
}
