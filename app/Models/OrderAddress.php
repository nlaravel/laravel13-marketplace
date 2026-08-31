<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderAddress extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'recipient_name',
        'phone',
        'country',
        'city',
        'area',
        'street',
        'building',
        'apartment',
        'address_line',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrderAddressType::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
