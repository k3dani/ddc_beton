<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'technical_name',
        'slug',
        'description',
        'short_description',
        'sku',
        'unit',
        'volume_cbm',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'volume_cbm' => 'decimal:3',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'category_product')
            ->withTimestamps();
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'location_product')
            ->withPivot('gross_price', 'net_price', 'is_available')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getPriceForLocation($locationId)
    {
        $location = $this->locations()->where('location_id', $locationId)->first();
        return $location ? $location->pivot->gross_price : null;
    }
}
