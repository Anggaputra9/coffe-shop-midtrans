<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'base_price' => 'integer',
        'subtotal' => 'integer',
        'quantity' => 'integer',
        'selected_options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getOptionsLabelAttribute(): string
    {
        $opts = $this->selected_options ?? [];
        $parts = [];
        if (!empty($opts['size'])) $parts[] = $opts['size'];
        if (!empty($opts['bean'])) $parts[] = $opts['bean'];
        if (!empty($opts['milk'])) $parts[] = $opts['milk'];
        if (!empty($opts['sweetness'])) $parts[] = 'Sweet: ' . $opts['sweetness'];
        if (!empty($opts['ice'])) $parts[] = 'Ice: ' . $opts['ice'];
        return implode(' · ', $parts);
    }
}
