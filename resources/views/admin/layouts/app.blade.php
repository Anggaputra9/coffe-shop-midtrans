<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Artisan Coffee</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
    @stack('head')
</head>
<body class="bg-cream font-sans text-coffee-dark antialiased">
<div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

    {{-- Backdrop (mobile) --}}
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-coffee-dark/50 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside x-cloak
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-cream-dark bg-white transition-transform duration-200 ease-out lg:static lg:z-auto lg:shrink-0">

        <div class="flex items-center justify-between border-b border-cream-dark px-6 py-5">
            <div class="flex items-center gap-2">
                <span class="text-2xl">☕</span>
                <div>
                    <p class="text-sm font-extrabold leading-tight text-coffee-dark">Artisan Coffee</p>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-warm-amber">Panel Admin</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-warm-gray transition hover:bg-cream hover:text-coffee-dark lg:hidden" aria-label="Tutup menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
                    ['route' => 'admin.products.index', 'label' => 'Kelola Menu', 'icon' => '🍵'],
                    ['route' => 'admin.income', 'label' => 'Pemasukan', 'icon' => '💰'],
                ];
            @endphp
            @foreach($navItems as $item)
                @php
                    $active = request()->routeIs($item['route'])
                        || ($item['route'] === 'admin.products.index' && request()->routeIs('admin.products.*'));
                @endphp
                <a href="{{ route($item['route']) }}" @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-coffee-dark text-cream' : 'text-warm-gray hover:bg-cream/70 hover:text-coffee-dark' }}">
                    <span>{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a href="/" target="_blank" @click="sidebarOpen = false"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-warm-gray transition hover:bg-cream/70 hover:text-coffee-dark">
                <span>🛍️</span>
                Lihat Toko
                <span class="text-[10px]">↗</span>
            </a>
        </nav>

        <div class="border-t border-cream-dark px-4 py-4">
            <p class="truncate text-xs font-semibold text-coffee-dark">{{ auth()->user()->name }}</p>
            <p class="truncate text-[11px] text-warm-gray">{{ auth()->user()->email }}</p>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full rounded-xl border border-cream-dark px-3 py-2 text-xs font-bold text-warm-gray transition hover:border-red-300 hover:bg-red-50 hover:text-red-600">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Content --}}
    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 flex items-center gap-3 border-b border-cream-dark bg-white/80 px-4 py-4 backdrop-blur-xl sm:px-6 lg:px-8">
            <button @click="sidebarOpen = true" class="rounded-xl border border-cream-dark p-2 text-coffee-dark transition hover:bg-cream lg:hidden" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="min-w-0">
                <h1 class="truncate text-lg font-bold text-coffee-dark sm:text-xl">@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <p class="hidden truncate text-sm text-warm-gray sm:block">@yield('subtitle')</p>
                @endif
            </div>
        </header>

        <div class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
