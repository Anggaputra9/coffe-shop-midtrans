<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:100'],
            'order_type' => ['required', 'in:dine_in,takeaway,pickup'],
            'table_or_notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'items.*.options' => ['nullable', 'array'],
            'items.*.options.size' => ['nullable', 'in:regular,large'],
            'items.*.options.bean' => ['nullable', 'in:house_blend,single_origin'],
            'items.*.options.milk' => ['nullable', 'in:dairy,oat_milk,almond_milk'],
            'items.*.options.sweetness' => ['nullable', 'in:normal,less,none'],
            'items.*.options.ice' => ['nullable', 'in:normal,less,none'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'nama pemesan',
            'order_type' => 'tipe pesanan',
            'items' => 'item pesanan',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang belanja tidak boleh kosong.',
            'items.min' => 'Minimal 1 item untuk checkout.',
        ];
    }
}
