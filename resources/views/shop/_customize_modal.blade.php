{{-- CUSTOMIZATION MODAL --}}
<template x-teleport="body">
    <div x-show="customizeOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] flex items-center justify-center bg-coffee-dark/60 p-4 backdrop-blur-sm" @click.self="customizeOpen = false">
        <div x-show="customizeOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="w-full max-w-lg overflow-hidden rounded-3xl border border-cream-dark bg-white shadow-2xl">
            <div class="relative h-40 overflow-hidden">
                <img :src="currentProduct?.image_url" :alt="currentProduct?.name" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-coffee-dark/80 to-transparent"></div>
                <button @click="customizeOpen = false" class="absolute right-3 top-3 rounded-full bg-white/20 p-1.5 text-white backdrop-blur hover:bg-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="absolute bottom-4 left-5">
                    <h3 class="text-xl font-bold text-white" x-text="currentProduct?.name"></h3>
                    <p class="text-sm text-cream/70">Mulai dari <span class="font-semibold text-warm-amber" x-text="formatRupiah(currentProduct?.base_price)"></span></p>
                </div>
            </div>
            <div class="max-h-[50vh] space-y-5 overflow-y-auto p-5">
                @include('shop._customize_options')
            </div>
            <div class="border-t border-cream-dark bg-cream/50 p-5">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-sm text-warm-gray">Unit Price</span>
                    <span class="text-xl font-bold text-coffee-dark" x-text="formatRupiah(computedUnitPrice)"></span>
                </div>
                <button @click="addToCart()" class="w-full rounded-2xl bg-coffee-dark py-3.5 text-center text-sm font-bold text-cream transition hover:bg-coffee-slate active:scale-[0.98]">
                    Tambahkan ke Keranjang
                </button>
            </div>
        </div>
    </div>
</template>
