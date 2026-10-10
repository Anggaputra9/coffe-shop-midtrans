<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show all transactions (cashier mode) with optional date & status filters.
     */
    public function history(Request $request)
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:pending,paid,expired,failed'],
        ]);

        $orders = Order::query()
            ->with('items.product')
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('payment_status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = Order::query()
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('payment_status', $status))
            ->selectRaw('count(*) as total_transactions, coalesce(sum(total_amount), 0) as gross_total')
            ->first();

        return view('orders.history', compact('orders', 'filters', 'stats'));
    }

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
