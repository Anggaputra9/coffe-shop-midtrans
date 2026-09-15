<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Show order status / invoice page by UUID.
     */
    public function show(string $uuid)
    {
        $order = Order::where('uuid', $uuid)->with('items.product')->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * API: return current order status (for polling).
     */
    public function status(string $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'paid_at' => $order->paid_at?->toDateTimeString(),
        ]);
    }
}
