<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();

        $products = Product::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")))
            ->when($category !== '', fn ($q) => $q->where('category', $category))
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', ['categories' => Product::CATEGORIES]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_available'] = $request->boolean('is_available', true);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Product::CATEGORIES,
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $data['is_available'] = $request->boolean('is_available');

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function toggleAvailability(Product $product): RedirectResponse
    {
        $product->update(['is_available' => ! $product->is_available]);

        $message = $product->is_available ? 'Menu ditandai tersedia.' : 'Menu ditandai tidak tersedia.';

        return back()->with('success', $message);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name) ?: 'menu';
        $base = $slug;
        $i = 1;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($q, $id) => $q->where('id', '!=', $id))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
