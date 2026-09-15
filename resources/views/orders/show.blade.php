@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Artisan Coffee')

@section('body')
<div class="min-h-screen bg-cream" x-data="orderTracker()" x-init="startPolling()">

    {{-- Header --}}
    <nav class="border-b border-cream-dark bg-white/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3">
            <a href="/" class="flex items-center gap-2 text-coffee-dark">
                <span class="text-xl">☕</span>
                <span class="text-sm font-bold">Artisan Coffee</span>
            </a>
            <span class="rounded-full bg-cream-dark px-3 py-1 text-xs font-semibold text-coffee-dark">{{ $order->order_number }}</span>
        </div>
    </nav>

    <div class="mx-auto max-w-3xl px-4 py-10">

        {{-- Status Card --}}
        <div class="mb-8 overflow-hidden rounded-3xl border border-cream-dark bg-white shadow-sm">
            <div class="bg-coffee-dark px-6 py-8 text-center">
                {{-- Status icon --}}
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full"
                    :class="{
                        'bg-green-500/20': paymentStatus === 'paid',
                        'bg-yellow-500/20 animate-pulse': paymentStatus === 'pending',
                        'bg-red-500/20': paymentStatus === 'failed',
                        'bg-gray-500/20': paymentStatus === 'expired',
                    }">
                    <span class="text-4xl" x-text="paymentStatus === 'paid' ? '✅' : paymentStatus === 'pending' ? '⏳' : paymentStatus === 'failed' ? '❌' : '⌛'"></span>
                </div>
                <h1 class="text-2xl font-bold text-cream">
                    <span x-text="paymentStatus === 'paid' ? 'Pembayaran Berhasil!' : paymentStatus === 'pending' ? 'Menunggu Pembayaran' : paymentStatus === 'failed' ? 'Pembayaran Gagal' : 'Pembayaran Expired'"></span>
                </h1>
                <p class="mt-2 text-sm text-cream/60" x-show="paymentStatus === 'pending'">Segera selesaikan pembayaran Anda</p>
                <p class="mt-2 text-sm text-cream/60" x-show="paymentStatus === 'paid'" x-text="'Dibayar pada ' + (paidAt || '')"></p>

                {{-- Status badge --}}
                <div class="mt-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold"
                    :class="{
                        'bg-green-500/20 text-green-400': paymentStatus === 'paid',
                        'bg-yellow-500/20 text-yellow-400': paymentStatus === 'pending',
                        'bg-red-500/20 text-red-400': paymentStatus === 'failed',
                        'bg-gray-500/20 text-gray-400': paymentStatus === 'expired',
                    }">
                    <span class="h-2 w-2 rounded-full" :class="{
                        'bg-green-400': paymentStatus === 'paid',
                        'bg-yellow-400 animate-pulse': paymentStatus === 'pending',
                        'bg-red-400': paymentStatus === 'failed',
                        'bg-gray-400': paymentStatus === 'expired',
                    }"></span>
                    <span x-text="paymentStatus.toUpperCase()"></span>
                </div>
            </div>
        </div>

        @include('orders._details')
    </div>
</div>
@endsection

@push('head')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none!important}</style>
@endpush

@push('scripts')
<script>
function orderTracker() {
    return {
        paymentStatus: '{{ $order->payment_status }}',
        paidAt: '{{ $order->paid_at?->format("d M Y H:i") }}',
        paymentMethod: '{{ $order->payment_method }}',
        intervalId: null,
        startPolling() {
            if (['paid','failed','expired'].includes(this.paymentStatus)) return;
            this.intervalId = setInterval(async () => {
                try {
                    const res = await fetch('/api/orders/{{ $order->uuid }}/status');
                    const data = await res.json();
                    this.paymentStatus = data.payment_status;
                    this.paymentMethod = data.payment_method || '';
                    this.paidAt = data.paid_at || '';
                    if (['paid','failed','expired'].includes(data.payment_status)) {
                        clearInterval(this.intervalId);
                    }
                } catch(e) {}
            }, 5000);
        },
    };
}
</script>
@endpush
