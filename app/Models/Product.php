<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public const CATEGORIES = [
        'espresso' => 'Espresso Based',
        'manual_brew' => 'Manual Brew',
        'non_coffee' => 'Non Coffee',
        'pastry' => 'Pastry & Snack',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'base_price' => 'integer',
        'is_available' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }
}
