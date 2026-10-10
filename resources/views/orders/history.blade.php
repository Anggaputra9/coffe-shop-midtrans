@extends('layouts.app')

@section('title', 'Riwayat Pembelian — Artisan Coffee')

@section('body')
<div class="min-h-screen bg-cream">

    {{-- Header --}}
    <nav class="border-b border-cream-dark bg-white/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <a href="/" class="flex items-center gap-2 text-coffee-dark">
                <span class="text-xl">☕</span>
                <span class="text-sm font-bold">Artisan Coffee</span>
            </a>
            <a href="/riwayat" class="rounded-full bg-coffee-dark px-4 py-1.5 text-xs font-semibold text-cream">Riwayat</a>
        </div>
    </nav>

    <div class="mx-auto max-w-5xl px-4 py-10">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-coffee-dark">Riwayat Pembelian</h1>
            <p class="mt-1 text-sm text-warm-gray">Semua transaksi yang tercatat di sistem kasir.</p>
        </div>

        {{-- Filter --}}
        <form action="{{ route('orders.history') }}" method="GET" class="mb-6 rounded-2xl border border-cream-dark bg-white p-5">
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
                    <a href="{{ route('orders.history') }}" title="Reset filter"
                        class="rounded-full border border-cream-dark px-4 py-2.5 text-sm font-semibold text-warm-gray transition hover:border-coffee-dark hover:text-coffee-dark">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Stats --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-cream-dark bg-white p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Total Transaksi</p>
                <p class="mt-1 text-2xl font-bold text-coffee-dark">{{ number_format($stats->total_transactions, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-cream-dark bg-white p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-warm-gray">Total Penjualan</p>
                <p class="mt-1 text-2xl font-bold text-warm-amber">Rp {{ number_format($stats->gross_total, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($orders->isEmpty())
            {{-- Empty state --}}
            <div class="rounded-2xl border border-cream-dark bg-white px-6 py-16 text-center">
                <span class="text-5xl">🧾</span>
                <p class="mt-4 text-lg font-semibold text-coffee-dark">Belum Ada Transaksi</p>
                <p class="mt-1 text-sm text-warm-gray">Tidak ada transaksi yang cocok dengan filter Anda.</p>
            </div>
        @else
            {{-- Transaction list --}}
            <div class="space-y-4">
                @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->uuid) }}"
                    class="block rounded-2xl border border-cream-dark bg-white p-5 transition hover:border-warm-amber/60 hover:shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-bold text-coffee-dark">{{ $order->order_number }}</span>
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $order->status_badge }}">
                                    {{ match($order->payment_status) {
                                        'paid' => 'Lunas',
                                        'pending' => 'Menunggu',
                                        'expired' => 'Kedaluwarsa',
                                        'failed' => 'Gagal',
                                        default => ucfirst($order->payment_status),
                                    } }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-warm-gray">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                · {{ $order->customer_name }}
                                · {{ str_replace('_', ' ', $order->order_type) }}
                            </p>
                        </div>
                        <span class="shrink-0 text-sm font-bold text-warm-amber">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-3 flex items-center gap-2 border-t border-cream-dark pt-3">
                        <div class="flex -space-x-2">
                            @foreach($order->items->take(3) as $item)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                    class="h-8 w-8 rounded-full border-2 border-white object-cover">
                            @endforeach
                        </div>
                        <p class="truncate text-xs text-warm-gray">
                            {{ $order->items->sum('quantity') }} item ·
                            {{ $order->items->take(2)->pluck('product.name')->implode(', ') }}{{ $order->items->count() > 2 ? ', +' . ($order->items->count() - 2) . ' lainnya' : '' }}
                        </p>
                        <span class="ml-auto shrink-0 text-xs font-semibold text-coffee-dark">Lihat Detail →</span>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif

        {{-- Back to shop --}}
        <div class="mt-10 text-center">
            <a href="/" class="inline-flex items-center gap-2 rounded-full bg-coffee-dark px-6 py-3 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
                ☕ Kembali ke Menu
            </a>
        </div>
    </div>
</div>
@endsection
