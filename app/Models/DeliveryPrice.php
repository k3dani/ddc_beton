<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'distance_from_km',
        'distance_to_km',
        'price_per_cbm',
    ];

    protected $casts = [
        'distance_from_km' => 'decimal:2',
        'distance_to_km' => 'decimal:2',
        'price_per_cbm' => 'decimal:2',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
