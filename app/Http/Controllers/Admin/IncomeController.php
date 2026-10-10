<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:pending,paid,expired,failed'],
        ]);

        $applyFilters = fn ($q) => $q
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('payment_status', $status));

        $orders = Order::query()
            ->tap($applyFilters)
            ->with('items.product')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = Order::query()
            ->tap($applyFilters)
            ->selectRaw("
                count(*) as total_transactions,
                coalesce(sum(case when payment_status = 'paid' then 1 else 0 end), 0) as paid_transactions,
                coalesce(sum(case when payment_status = 'paid' then total_amount else 0 end), 0) as paid_total,
                coalesce(avg(case when payment_status = 'paid' then total_amount end), 0) as avg_paid
            ")
            ->first();

        $todayIncome = (int) Order::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('total_amount');

        $monthIncome = (int) Order::where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('total_amount');

        return view('admin.income.index', compact('orders', 'filters', 'stats', 'todayIncome', 'monthIncome'));
    }
}
