{{-- CHECKOUT MODAL --}}
<template x-teleport="body">
    <div x-show="checkoutOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[90] flex items-center justify-center overflow-y-auto bg-coffee-dark/60 p-4 backdrop-blur-sm" @click.self="checkoutOpen = false">
        <div x-show="checkoutOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="w-full max-w-lg rounded-3xl border border-cream-dark bg-white shadow-2xl">
            <div class="border-b border-cream-dark px-6 py-4">
                <h2 class="text-lg font-bold text-coffee-dark">Checkout</h2>
                <p class="text-sm text-warm-gray">Lengkapi data untuk melanjutkan pembayaran</p>
            </div>
            <form @submit.prevent="processCheckout()" class="space-y-4 px-6 py-5">
                @include('shop._checkout_form')
            </form>
        </div>
    </div>
</template>
