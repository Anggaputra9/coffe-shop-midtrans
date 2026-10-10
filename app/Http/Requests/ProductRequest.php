<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('products', 'name')->ignore($this->route('product'))],
            'category' => ['required', 'in:'.implode(',', array_keys(Product::CATEGORIES))],
            'description' => ['nullable', 'string', 'max:500'],
            'base_price' => ['required', 'integer', 'min:0', 'max:10000000'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama menu',
            'category' => 'kategori',
            'description' => 'deskripsi',
            'base_price' => 'harga dasar',
            'image_url' => 'URL gambar',
            'is_available' => 'status tersedia',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama menu sudah digunakan.',
            'category.in' => 'Kategori tidak valid.',
            'image_url.url' => 'URL gambar tidak valid.',
            'base_price.integer' => 'Harga dasar harus berupa angka.',
            'base_price.min' => 'Harga dasar tidak boleh negatif.',
        ];
    }
}
