<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'service_fee' => 'integer',
        'total_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->uuid = $order->uuid ?: (string) Str::uuid();
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isFinal(): bool
    {
        return in_array($this->payment_status, ['paid', 'expired', 'failed']);
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::where('order_number', 'like', "INV-{$date}-%")->count();
        return sprintf('INV-%s-%04d', $date, $last + 1);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'expired' => 'bg-gray-100 text-gray-600',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
