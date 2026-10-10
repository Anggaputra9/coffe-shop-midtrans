@extends('admin.layouts.app')

@section('title', 'Pemasukan')
@section('subtitle', 'Catatan pemasukan dari transaksi yang lunas.')

@section('content')
{{-- Filter --}}
<form action="{{ route('admin.income') }}" method="GET" class="mb-6 rounded-2xl border border-cream-dark bg-white p-5">
    <div class="grid gap-4 sm:grid-cols-4">
        <div>
            <label for="date_from" class="mb-1.5 block text-xs font-semibold text-warm-gray">Dari Tanggal</label>
            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
        </div>
        <div>
            <label for="date_to" class="mb-1.5 block text-xs font-semibold text-warm-gray">Sampai Tanggal</label>
            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
        </div>
        <div>
            <label for="status" class="mb-1.5 block text-xs font-semibold text-warm-gray">Status</label>
            <select id="status" name="status"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                <option value="">Semua Status</option>
                @foreach(['paid' => 'Lunas', 'pending' => 'Menunggu', 'expired' => 'Kedaluwarsa', 'failed' => 'Gagal'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 rounded-full bg-coffee-dark px-5 py-2.5 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
                Terapkan
            </button>
            <a href="{{ route('admin.income') }}" title="Reset filter"
                class="rounded-full border border-cream-dark px-4 py-2.5 text-sm font-semibold text-warm-gray transition hover:border-coffee-dark hover:text-coffee-dark">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Stats --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Pemasukan Hari Ini</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">Rp {{ number_format($todayIncome, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Pemasukan Bulan Ini</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">Rp {{ number_format($monthIncome, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Total Pemasukan (Filter)</p>
        <p class="mt-1 text-2xl font-bold text-warm-amber">Rp {{ number_format($stats->paid_total, 0, ',', '.') }}</p>
        <p class="mt-1 text-[11px] font-semibold text-warm-gray">{{ $stats->paid_transactions }} dari {{ $stats->total_transactions }} transaksi lunas</p>
    </div>
    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Rata-rata per Transaksi</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">Rp {{ number_format($stats->avg_paid, 0, ',', '.') }}</p>
    </div>
</div>

@if($orders->isEmpty())
    <div class="rounded-2xl border border-cream-dark bg-white px-6 py-16 text-center">
        <span class="text-5xl">💰</span>
        <p class="mt-4 text-lg font-semibold text-coffee-dark">Belum Ada Catatan</p>
        <p class="mt-1 text-sm text-warm-gray">Tidak ada transaksi yang cocok dengan filter Anda.</p>
    </div>
@else
    <div class="overflow-x-auto rounded-2xl border border-cream-dark bg-white">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="border-b border-cream-dark bg-cream/50 text-[11px] uppercase tracking-wider text-warm-gray">
                <tr>
                    <th class="px-5 py-3 font-bold">Invoice</th>
                    <th class="px-5 py-3 font-bold">Tanggal Bayar</th>
                    <th class="px-5 py-3 font-bold">Pelanggan</th>
                    <th class="px-5 py-3 font-bold">Item</th>
                    <th class="px-5 py-3 font-bold">Status</th>
                    <th class="px-5 py-3 text-right font-bold">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream-dark">
                @foreach($orders as $order)
                    <tr class="transition hover:bg-cream/40">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('orders.show', $order->uuid) }}" class="font-bold text-coffee-dark transition hover:text-warm-amber">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-warm-gray">
                            {{ $order->paid_at?->translatedFormat('d M Y, H:i') ?? $order->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-coffee-dark">{{ $order->customer_name }}</p>
                            <p class="text-xs text-warm-gray">{{ str_replace('_', ' ', $order->order_type) }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-warm-gray">{{ $order->items->sum('quantity') }} item</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $order->status_badge }}">
                                {{ match($order->payment_status) {
                                    'paid' => 'Lunas', 'pending' => 'Menunggu',
                                    'expired' => 'Kedaluwarsa', 'failed' => 'Gagal',
                                    default => ucfirst($order->payment_status),
                                } }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-warm-amber">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
@endif
@endsection
