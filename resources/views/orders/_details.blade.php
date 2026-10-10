{{-- Order detail breakdown --}}
<div class="space-y-6">
    {{-- Customer Info --}}
    <div class="rounded-2xl border border-cream-dark bg-white p-6">
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-warm-gray">Informasi Pemesan</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-warm-gray">Atas Nama</p><p class="font-semibold text-coffee-dark">{{ $order->customer_name }}</p></div>
            <div><p class="text-warm-gray">Tipe</p><p class="font-semibold text-coffee-dark capitalize">{{ str_replace('_', ' ', $order->order_type) }}</p></div>
            @if($order->customer_email)
            <div><p class="text-warm-gray">Email</p><p class="font-semibold text-coffee-dark">{{ $order->customer_email }}</p></div>
            @endif
            @if($order->customer_phone)
            <div><p class="text-warm-gray">WhatsApp</p><p class="font-semibold text-coffee-dark">{{ $order->customer_phone }}</p></div>
            @endif
            @if($order->table_or_notes)
            <div class="col-span-2"><p class="text-warm-gray">Catatan</p><p class="font-semibold text-coffee-dark">{{ $order->table_or_notes }}</p></div>
            @endif
        </div>
    </div>

    {{-- Order Log --}}
    <div class="rounded-2xl border border-cream-dark bg-white p-6">
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-warm-gray">Log Pesanan</h3>
        <ol class="relative space-y-6 border-l-2 border-cream-dark pl-6">
            <li class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-warm-amber text-[10px]">🛒</span>
                <p class="text-sm font-bold text-coffee-dark">Pesanan dibuat</p>
                <p class="text-xs text-warm-gray">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </li>
            @if($order->paid_at)
            <li class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-green-500 text-[10px]">✅</span>
                <p class="text-sm font-bold text-coffee-dark">Pembayaran diterima{{ $order->payment_method ? ' (' . strtoupper($order->payment_method) . ')' : '' }}</p>
                <p class="text-xs text-warm-gray">{{ $order->paid_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </li>
            @elseif($order->payment_status === 'expired')
            <li class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-gray-400 text-[10px]">⌛</span>
                <p class="text-sm font-bold text-coffee-dark">Pembayaran kedaluwarsa</p>
                <p class="text-xs text-warm-gray">{{ $order->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </li>
            @elseif($order->payment_status === 'failed')
            <li class="relative">
                <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px]">✕</span>
                <p class="text-sm font-bold text-coffee-dark">Pembayaran gagal</p>
                <p class="text-xs text-warm-gray">{{ $order->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </li>
            @endif
        </ol>
    </div>

    {{-- Items --}}
    <div class="rounded-2xl border border-cream-dark bg-white p-6">
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-warm-gray">Detail Pesanan</h3>
        @foreach($order->items as $item)
        <div class="flex items-start gap-4 border-b border-cream-dark py-4 last:border-0">
            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-14 w-14 rounded-xl object-cover">
            <div class="flex-1">
                <div class="flex justify-between">
                    <h4 class="text-sm font-bold text-coffee-dark">{{ $item->product->name }}</h4>
                    <span class="text-sm font-semibold text-coffee-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <p class="mt-0.5 text-xs text-warm-gray">{{ $item->options_label }}</p>
                <p class="text-xs text-warm-gray">Qty: {{ $item->quantity }} × Rp {{ number_format($item->subtotal / $item->quantity, 0, ',', '.') }}</p>
            </div>
        </div>
        @endforeach

        <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between text-warm-gray"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-warm-gray"><span>PPN 11%</span><span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-warm-gray"><span>Service Fee</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
            <div class="flex justify-between border-t border-cream-dark pt-2 text-base font-bold text-coffee-dark">
                <span>Total</span><span class="text-warm-amber">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Back to shop --}}
    <div class="text-center">
        <a href="/" class="inline-flex items-center gap-2 rounded-full bg-coffee-dark px-6 py-3 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
            ☕ Pesan Lagi
        </a>
    </div>
</div>
