<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — Artisan Coffee</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-cream px-4 font-sans text-coffee-dark antialiased">
    <div class="w-full max-w-md">

        <div class="mb-8 text-center">
            <span class="text-5xl">☕</span>
            <h1 class="mt-3 text-2xl font-extrabold text-coffee-dark">Artisan Coffee & Roastery</h1>
            <p class="mt-1 text-sm font-semibold text-warm-amber">Login Panel Admin</p>
        </div>

        <div class="rounded-2xl border border-cream-dark bg-white p-8 shadow-sm">
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-xs font-semibold text-warm-gray">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-semibold text-warm-gray">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                        class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                </div>

                <label class="flex items-center gap-2 text-sm text-warm-gray">
                    <input type="checkbox" name="remember" value="1" class="rounded border-cream-dark text-warm-amber focus:ring-warm-amber/30">
                    Ingat saya
                </label>

                <button type="submit"
                    class="w-full rounded-full bg-coffee-dark px-5 py-3 text-sm font-bold text-cream transition hover:bg-coffee-slate">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-warm-gray">
            <a href="/" class="font-semibold transition hover:text-coffee-dark">← Kembali ke toko</a>
        </p>
    </div>
</body>
</html>
