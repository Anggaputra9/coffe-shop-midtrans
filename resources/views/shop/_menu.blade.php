{{-- MENU SECTION --}}
<main id="menu" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    @foreach($products as $category => $items)
    <section id="cat-{{ $category }}" class="mb-16 scroll-mt-24">
        <div class="mb-8 flex items-center gap-3">
            <span class="text-2xl">
                @switch($category)
                    @case('espresso') ☕ @break
                    @case('manual_brew') 🫖 @break
                    @case('non_coffee') 🍵 @break
                    @case('pastry') 🥐 @break
                @endswitch
            </span>
            <div>
                <h2 class="text-2xl font-bold text-coffee-dark">{{ str_replace('_', ' ', ucwords($category, '_')) }}</h2>
                <div class="mt-0.5 h-0.5 w-12 rounded-full bg-warm-amber"></div>
            </div>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($items as $product)
            <div class="group relative overflow-hidden rounded-2xl border border-cream-dark bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-warm-amber/5">
                <div class="relative h-52 overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-coffee-dark/60 to-transparent"></div>
                    <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-coffee-dark backdrop-blur">{{ str_replace('_', ' ', $category) }}</span>
                    @if($product->is_available)
                    <span class="absolute right-3 top-3 flex items-center gap-1 rounded-full bg-green-500/90 px-2 py-0.5 text-[10px] font-semibold text-white backdrop-blur">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span> Ready
                    </span>
                    @else
                    <span class="absolute right-3 top-3 rounded-full bg-red-500/90 px-2 py-0.5 text-[10px] font-semibold text-white backdrop-blur">Sold Out</span>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-bold text-coffee-dark">{{ $product->name }}</h3>
                    <p class="mt-1 line-clamp-2 text-sm text-warm-gray">{{ $product->description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-warm-amber">{{ $product->formatted_price }}</span>
                        @if($product->is_available)
                        <button @click='openCustomize(@json($product))' class="flex items-center gap-1.5 rounded-full bg-coffee-dark px-4 py-2 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Customize
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach
</main>
