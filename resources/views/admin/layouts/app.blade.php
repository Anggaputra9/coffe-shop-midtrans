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
    @stack('head')
</head>
<body class="bg-cream font-sans text-coffee-dark antialiased">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="flex w-64 shrink-0 flex-col border-r border-cream-dark bg-white">
        <div class="flex items-center gap-2 border-b border-cream-dark px-6 py-5">
            <span class="text-2xl">☕</span>
            <div>
                <p class="text-sm font-extrabold leading-tight text-coffee-dark">Artisan Coffee</p>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-warm-amber">Panel Admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 px-3 py-4">
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
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-coffee-dark text-cream' : 'text-warm-gray hover:bg-cream/70 hover:text-coffee-dark' }}">
                    <span>{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a href="/" target="_blank"
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
        <header class="border-b border-cream-dark bg-white/70 px-8 py-5">
            <h1 class="text-xl font-bold text-coffee-dark">@yield('title', 'Dashboard')</h1>
            @hasSection('subtitle')
                <p class="mt-0.5 text-sm text-warm-gray">@yield('subtitle')</p>
            @endif
        </header>

        <div class="px-8 py-8">
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
