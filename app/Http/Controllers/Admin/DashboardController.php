<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'available_products' => Product::where('is_available', true)->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'today_income' => (int) Order::where('payment_status', 'paid')
                ->whereDate('paid_at', today())
                ->sum('total_amount'),
            'month_income' => (int) Order::where('payment_status', 'paid')
                ->where('paid_at', '>=', now()->startOfMonth())
                ->sum('total_amount'),
        ];

        $latestOrders = Order::with('items.product')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}
