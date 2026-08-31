<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\SellerOrderStatus;
class SellerOrder extends Model
{
    protected $fillable = [
        'order_id',
        'store_id',
        'status',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'tax_amount',
        'commission_amount',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'status' => SellerOrderStatus::class,
            'subtotal' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
