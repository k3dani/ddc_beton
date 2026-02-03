<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'is_company',
        'company_name',
        'tax_number',
        'name',
        'email',
        'phone',
        'address',
        'billing_postal_code',
        'billing_city',
        'billing_street',
        'billing_house_number',
        'delivery_address',
        'delivery_postal_code',
        'delivery_city',
        'delivery_street',
        'delivery_house_number',
        'notes',
        'subtotal',
        'total',
        'status',
        'needs_delivery',
        'delivery_distance_km',
        'delivery_volume_cbm',
        'delivery_price',
        'pump_id',
        'pump_type',
        'pump_fixed_fee',
        'pump_hourly_fee',
        'pump_estimated_hours',
    ];

    protected $casts = [
        'is_company' => 'boolean',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'needs_delivery' => 'boolean',
        'delivery_distance_km' => 'decimal:2',
        'delivery_volume_cbm' => 'decimal:3',
        'delivery_price' => 'decimal:2',
        'pump_fixed_fee' => 'decimal:2',
        'pump_hourly_fee' => 'decimal:2',
        'pump_estimated_hours' => 'decimal:2',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
