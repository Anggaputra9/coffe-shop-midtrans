@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas toko hari ini.')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Pemasukan Hari Ini</p>
        <p class="mt-1 text-2xl font-bold text-warm-amber">Rp {{ number_format($stats['today_income'], 0, ',', '.') }}</p>
    </div>

    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Pemasukan Bulan Ini</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">Rp {{ number_format($stats['month_income'], 0, ',', '.') }}</p>
    </div>

    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Transaksi Hari Ini</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">{{ number_format($stats['today_orders'], 0, ',', '.') }}</p>
        <p class="mt-1 text-[11px] font-semibold text-warm-gray">{{ $stats['pending_orders'] }} menunggu pembayaran</p>
    </div>

    <div class="rounded-2xl border border-cream-dark bg-white p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Menu Aktif</p>
        <p class="mt-1 text-2xl font-bold text-coffee-dark">{{ $stats['available_products'] }} <span class="text-base font-semibold text-warm-gray">/ {{ $stats['total_products'] }}</span></p>
    </div>

</div>

<div class="mt-8">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-base font-bold text-coffee-dark">Transaksi Terbaru</h2>
        <a href="{{ route('admin.income') }}" class="text-xs font-bold text-warm-amber transition hover:text-coffee-dark">Lihat semua →</a>
    </div>

    @if($latestOrders->isEmpty())
        <div class="rounded-2xl border border-cream-dark bg-white px-6 py-12 text-center">
            <span class="text-4xl">🧾</span>
            <p class="mt-3 text-sm font-semibold text-warm-gray">Belum ada transaksi.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-cream-dark bg-white">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-cream-dark bg-cream/50 text-[11px] uppercase tracking-wider text-warm-gray">
                    <tr>
                        <th class="px-5 py-3 font-bold">Invoice</th>
                        <th class="px-5 py-3 font-bold">Pelanggan</th>
                        <th class="px-5 py-3 font-bold">Tanggal</th>
                        <th class="px-5 py-3 font-bold">Status</th>
                        <th class="px-5 py-3 text-right font-bold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-dark">
                    @foreach($latestOrders as $order)
                        <tr class="transition hover:bg-cream/40">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('orders.show', $order->uuid) }}" class="font-bold text-coffee-dark transition hover:text-warm-amber">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-warm-gray">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3.5 text-warm-gray">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
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
    @endif
</div>
@endsection
