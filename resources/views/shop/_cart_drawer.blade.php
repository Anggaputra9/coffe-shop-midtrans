{{-- CART SLIDE-OVER DRAWER --}}
{{-- Backdrop --}}
<template x-teleport="body">
    <div x-show="cartOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[70] bg-coffee-dark/50 backdrop-blur-sm" @click="cartOpen = false"></div>
</template>
{{-- Panel --}}
<template x-teleport="body">
    <div x-show="cartOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-[80] flex w-full max-w-md flex-col bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-cream-dark px-6 py-4">
            <div>
                <h2 class="text-lg font-bold text-coffee-dark">Keranjang Anda</h2>
                <p class="text-sm text-warm-gray"><span x-text="cartCount"></span> item</p>
            </div>
            <button @click="cartOpen = false" class="rounded-full p-2 text-warm-gray transition hover:bg-cream-dark hover:text-coffee-dark">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <span class="text-5xl">🛒</span>
                    <p class="mt-4 text-lg font-semibold text-coffee-dark">Keranjang Kosong</p>
                    <p class="mt-1 text-sm text-warm-gray">Mulai pilih kopi favoritmu!</p>
                </div>
            </template>
            <template x-for="(item, index) in cart" :key="index">
                <div class="mb-4 rounded-2xl border border-cream-dark bg-cream/30 p-4">
                    <div class="flex items-start gap-3">
                        <img :src="item.image_url" :alt="item.name" class="h-16 w-16 rounded-xl object-cover">
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <h4 class="text-sm font-bold text-coffee-dark" x-text="item.name"></h4>
                                <button @click="removeFromCart(index)" class="text-warm-gray transition hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                            <p class="mt-0.5 text-[11px] text-warm-gray" x-text="item.optionsLabel"></p>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center gap-2 rounded-lg border border-cream-dark">
                                    <button @click="updateQty(index, -1)" class="px-2.5 py-1 text-warm-gray hover:text-coffee-dark">−</button>
                                    <span class="min-w-[20px] text-center text-sm font-semibold" x-text="item.quantity"></span>
                                    <button @click="updateQty(index, 1)" class="px-2.5 py-1 text-warm-gray hover:text-coffee-dark">+</button>
                                </div>
                                <span class="text-sm font-bold text-warm-amber" x-text="formatRupiah(item.unitPrice * item.quantity)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="cart.length > 0" class="border-t border-cream-dark bg-cream/50 px-6 py-5">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-warm-gray"><span>Subtotal</span><span x-text="formatRupiah(cartSubtotal)"></span></div>
                <div class="flex justify-between text-warm-gray"><span>PPN 11%</span><span x-text="formatRupiah(cartTax)"></span></div>
                <div class="flex justify-between text-warm-gray"><span>Service Fee</span><span>Rp 2.000</span></div>
                <div class="flex justify-between border-t border-cream-dark pt-2 text-base font-bold text-coffee-dark"><span>Total</span><span x-text="formatRupiah(cartTotal)"></span></div>
            </div>
            <button @click="cartOpen = false; checkoutOpen = true" class="mt-4 w-full rounded-2xl bg-warm-amber py-3.5 text-center text-sm font-bold text-white shadow-lg shadow-warm-amber/25 transition hover:bg-amber-light active:scale-[0.98]">
                Checkout — <span x-text="formatRupiah(cartTotal)"></span>
            </button>
        </div>
    </div>
</template>
