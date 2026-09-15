@extends('layouts.app')

@section('title', 'Artisan Specialty Coffee & Roastery')

@section('body')
<div x-data="coffeeShop()" x-cloak>

    {{-- NAVBAR --}}
    <nav class="fixed top-0 inset-x-0 z-50 border-b border-white/10 backdrop-blur-xl bg-coffee-dark/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl">☕</span>
                <div class="leading-tight">
                    <span class="block text-lg font-bold tracking-tight text-cream">Artisan</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.25em] text-warm-amber">Specialty Coffee & Roastery</span>
                </div>
            </a>
            <div class="hidden items-center gap-1 md:flex">
                <template x-for="cat in categories" :key="cat.key">
                    <button @click="scrollToCategory(cat.key)"
                        class="rounded-full px-4 py-1.5 text-sm font-medium text-cream/70 transition hover:bg-white/10 hover:text-cream"
                        :class="{ 'bg-warm-amber/20 !text-warm-amber': activeCategory === cat.key }"
                        x-text="cat.label"></button>
                </template>
            </div>
            <button @click="cartOpen = true" class="relative rounded-full bg-warm-amber/10 p-2.5 text-warm-amber transition hover:bg-warm-amber/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                <span x-show="cartCount > 0" x-text="cartCount" x-transition class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-warm-amber text-[11px] font-bold text-white"></span>
            </button>
        </div>
    </nav>

    {{-- HERO --}}
    <header class="relative flex min-h-[60vh] items-center justify-center overflow-hidden bg-coffee-dark pt-16">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1600&q=80')] bg-cover bg-center opacity-30"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-coffee-dark/60 via-coffee-dark/40 to-cream"></div>
        <div class="relative z-10 mx-auto max-w-3xl px-4 text-center">
            <p class="mb-3 mt-8 text-sm font-semibold uppercase tracking-[0.3em] text-warm-amber">Roasted with Passion</p>
            <h1 class="text-4xl font-extrabold leading-tight text-cream sm:text-5xl md:text-6xl">Craft Coffee,<br><span class="text-warm-amber">Extraordinary</span> Taste</h1>
            <p class="mx-auto mt-4 max-w-lg text-base text-cream/60">Setiap cangkir diracik dengan biji kopi pilihan dari petani lokal terbaik. Rasakan perjalanan rasa yang tak terlupakan.</p>
            <button @click="document.getElementById('menu').scrollIntoView({behavior:'smooth'})" class="mt-8 mb-4 inline-flex items-center gap-2 rounded-full bg-warm-amber px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-warm-amber/25 transition hover:bg-amber-light">
                Jelajahi Menu
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    </header>

    @include('shop._menu')
    @include('shop._customize_modal')
    @include('shop._cart_drawer')
    @include('shop._checkout_modal')

    {{-- FOOTER --}}
    <footer class="border-t border-cream-dark bg-coffee-dark py-12 text-cream/60">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <p class="text-2xl">☕</p>
            <p class="mt-2 text-sm font-semibold text-cream">Artisan Specialty Coffee & Roastery</p>
            <p class="mt-1 text-xs">Crafted with love. Powered by passion and great beans.</p>
            <p class="mt-4 text-xs text-cream/30">&copy; {{ date('Y') }} Artisan Coffee.</p>
        </div>
    </footer>
</div>
@endsection

@push('head')
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none!important}</style>
@endpush

@push('scripts')
@include('shop._scripts')
@endpush
