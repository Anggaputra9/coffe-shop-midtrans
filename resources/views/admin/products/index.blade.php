@extends('admin.layouts.app')

@section('title', 'Kelola Menu')
@section('subtitle', 'Tambah, ubah, dan atur ketersediaan menu.')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">

    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-1 flex-wrap items-end gap-3">
        <div class="min-w-48 flex-1">
            <label for="search" class="mb-1.5 block text-xs font-semibold text-warm-gray">Cari</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama atau kategori..."
                class="w-full rounded-xl border border-cream-dark bg-white px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
        </div>
        <div>
            <label for="category" class="mb-1.5 block text-xs font-semibold text-warm-gray">Kategori</label>
            <select id="category" name="category"
                class="rounded-xl border border-cream-dark bg-white px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                <option value="">Semua</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-full bg-coffee-dark px-5 py-2.5 text-sm font-semibold text-cream transition hover:bg-coffee-slate">
            Filter
        </button>
    </form>

    <a href="{{ route('admin.products.create') }}"
        class="rounded-full bg-warm-amber px-5 py-2.5 text-sm font-bold text-white transition hover:bg-amber-light">
        + Tambah Menu
    </a>
</div>

@if($products->isEmpty())
    <div class="rounded-2xl border border-cream-dark bg-white px-6 py-16 text-center">
        <span class="text-5xl">🍵</span>
        <p class="mt-4 text-lg font-semibold text-coffee-dark">Belum Ada Menu</p>
        <p class="mt-1 text-sm text-warm-gray">Tambahkan menu pertama Anda.</p>
    </div>
@else
    <div class="overflow-x-auto rounded-2xl border border-cream-dark bg-white">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="border-b border-cream-dark bg-cream/50 text-[11px] uppercase tracking-wider text-warm-gray">
                <tr>
                    <th class="px-5 py-3 font-bold">Menu</th>
                    <th class="px-5 py-3 font-bold">Kategori</th>
                    <th class="px-5 py-3 font-bold">Harga</th>
                    <th class="px-5 py-3 font-bold">Status</th>
                    <th class="px-5 py-3 text-right font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream-dark">
                @foreach($products as $product)
                    <tr class="transition hover:bg-cream/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-xl object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cream text-lg">☕</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-coffee-dark">{{ $product->name }}</p>
                                    @if($product->description)
                                        <p class="truncate text-xs text-warm-gray">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full bg-cream px-2.5 py-0.5 text-[11px] font-semibold text-coffee-dark">
                                {{ $categories[$product->category] ?? ucfirst($product->category) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-warm-amber">{{ $product->formatted_price }}</td>
                        <td class="px-5 py-3.5">
                            <form action="{{ route('admin.products.toggle', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Klik untuk mengubah status"
                                    class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold transition {{ $product->is_available ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="rounded-full border border-cream-dark px-3.5 py-1.5 text-xs font-bold text-coffee-dark transition hover:border-warm-amber hover:text-warm-amber">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Hapus menu \"{{ $product->name }}\"? Riwayat pesanan lama tetap tersimpan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-full border border-cream-dark px-3.5 py-1.5 text-xs font-bold text-warm-gray transition hover:border-red-300 hover:bg-red-50 hover:text-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
