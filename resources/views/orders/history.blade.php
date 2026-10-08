@extends('layouts.app')

@section('title', 'Riwayat Pembelian — Artisan Coffee')

@section('body')
<div class="min-h-screen bg-cream">

    {{-- Header --}}
    <nav class="border-b border-cream-dark bg-white/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3">
            <a href="/" class="flex items-center gap-2 text-coffee-dark">
                <span class="text-xl">☕</span>
                <span class="text-sm font-bold">Artisan Coffee</span>
            </a>
            <a href="/riwayat" class="rounded-full bg-coffee-dark px-4 py-1.5 text-xs font-semibold text-cream">Riwayat</a>
        </div>
    </nav>

    <div class="mx-auto max-w-3xl px-4 py-10">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-coffee-dark">Riwayat Pembelian</h1>
            <p class="mt-1 text-sm text-warm-gray">Semua pesanan yang pernah Anda buat di Artisan Coffee.</p>
        </div>

        @if(! $email)
            {{-- Email lookup form --}}
            <div class="rounded-2xl border border-cream-dark bg-white p-6">
                <div class="mb-4 flex flex-col items-center py-6 text-center">
                    <span class="text-5xl">🧾</span>
                    <p class="mt-4 text-lg font-semibold text-coffee-dark">Lihat Riwayat Pesanan Anda</p>
                    <p class="mt-1 text-sm text-warm-gray">Masukkan email yang digunakan saat checkout.</p>
                </div>
                <form action="{{ route('orders.history.lookup') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="customer_email" class="mb-1.5 block text-sm font-semibold text-coffee-dark">Email</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}"
                            required autofocus placeholder="nama@email.com"
                            class="w-full rounded-xl border border-cream-dark bg-cream/40 px-4 py-3 text-sm text-coffee-dark placeholder:text-warm-gray focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                        @error('customer_email')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-full bg-coffee-dark px-6 py-3 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
                        Lihat Riwayat
                    </button>
                </form>
            </div>
        @else
            {{-- Account bar --}}
            <div class="mb-6 flex items-center justify-between gap-4 rounded-2xl border border-cream-dark bg-white px-5 py-4">
                <div class="min-w-0">
                    <p class="text-xs text-warm-gray">Riwayat untuk</p>
                    <p class="truncate text-sm font-semibold text-coffee-dark">{{ $email }}</p>
                </div>
                <form action="{{ route('orders.history.forget') }}" method="POST">
                    @csrf
                    <button type="submit" class="shrink-0 rounded-full border border-cream-dark px-4 py-2 text-xs font-semibold text-warm-gray transition hover:border-coffee-dark hover:text-coffee-dark">
                        Ganti Email
                    </button>
                </form>
            </div>

            @if($orders->isEmpty())
                {{-- Empty state --}}
                <div class="rounded-2xl border border-cream-dark bg-white px-6 py-16 text-center">
                    <span class="text-5xl">🛒</span>
                    <p class="mt-4 text-lg font-semibold text-coffee-dark">Belum Ada Pesanan</p>
                    <p class="mt-1 text-sm text-warm-gray">Pesanan Anda akan muncul di sini setelah checkout.</p>
                    <a href="/" class="mt-6 inline-flex items-center gap-2 rounded-full bg-coffee-dark px-6 py-3 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
                        ☕ Pesan Sekarang
                    </a>
                </div>
            @else
                {{-- Order list --}}
                <div class="space-y-4">
                    @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order->uuid) }}"
                        class="block rounded-2xl border border-cream-dark bg-white p-5 transition hover:border-warm-amber/60 hover:shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
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
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }} · {{ str_replace('_', ' ', $order->order_type) }}
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
