<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show purchase history scoped to the email stored in session.
     */
    public function history(Request $request)
    {
        $email = session('customer_email');

        $orders = $email
            ? Order::where('customer_email', $email)
                ->with('items.product')
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : null;

        return view('orders.history', compact('orders', 'email'));
    }

    /**
     * Store the lookup email in session and redirect to history.
     */
    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'customer_email' => ['required', 'email'],
        ], [], ['customer_email' => 'Email']);

        session(['customer_email' => $validated['customer_email']]);

        return redirect()->route('orders.history');
    }

    /**
     * Forget the session email (switch account).
     */
    public function forgetEmail()
    {
        session()->forget('customer_email');

        return redirect()->route('orders.history');
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
