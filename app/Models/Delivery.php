<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'seller_order_id',
        'delivery_profile_id',
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'failed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatus::class,
            'assigned_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'delivered_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function sellerOrder(): BelongsTo
    {
        return $this->belongsTo(SellerOrder::class);
    }

    public function deliveryProfile(): BelongsTo
    {
        return $this->belongsTo(DeliveryProfile::class);
    }
}
