@php $isEdit = isset($product) && $product; @endphp

<div class="max-w-2xl">
    <form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" class="space-y-5 rounded-2xl border border-cream-dark bg-white p-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold text-warm-gray">Nama Menu <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="120"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="category" class="mb-1.5 block text-xs font-semibold text-warm-gray">Kategori <span class="text-red-500">*</span></label>
                <select id="category" name="category" required
                    class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" @selected(old('category', $product->category ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="base_price" class="mb-1.5 block text-xs font-semibold text-warm-gray">Harga Dasar (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="base_price" name="base_price" value="{{ old('base_price', $product->base_price ?? '') }}" required min="0" step="1000"
                    class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
            </div>
        </div>

        <div>
            <label for="description" class="mb-1.5 block text-xs font-semibold text-warm-gray">Deskripsi</label>
            <textarea id="description" name="description" rows="3" maxlength="500"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="image_url" class="mb-1.5 block text-xs font-semibold text-warm-gray">URL Gambar</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url ?? '') }}" placeholder="https://..." maxlength="500"
                class="w-full rounded-xl border border-cream-dark bg-cream/40 px-3 py-2.5 text-sm text-coffee-dark focus:border-warm-amber focus:outline-none focus:ring-2 focus:ring-warm-amber/20">
        </div>

        <label class="flex items-center gap-2.5 text-sm font-semibold text-coffee-dark">
            <input type="hidden" name="is_available" value="0">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $product->is_available ?? true)) class="rounded border-cream-dark text-warm-amber focus:ring-warm-amber/30">
            Tersedia di menu
        </label>

        <div class="flex items-center gap-3 border-t border-cream-dark pt-5">
            <button type="submit"
                class="rounded-full bg-coffee-dark px-6 py-2.5 text-sm font-bold text-cream transition hover:bg-coffee-slate">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Menu' }}
            </button>
            <a href="{{ route('admin.products.index') }}"
                class="rounded-full border border-cream-dark px-6 py-2.5 text-sm font-semibold text-warm-gray transition hover:border-coffee-dark hover:text-coffee-dark">
                Batal
            </a>
        </div>
    </form>
</div>
