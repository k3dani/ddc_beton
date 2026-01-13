<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            // Slug generálás
            if (empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
            
            // Koordináták automatikus lekérése, ha hiányoznak
            if ((empty($location->latitude) || empty($location->longitude)) && !empty($location->city)) {
                static::geocodeLocation($location);
            }
        });

        static::updating(function ($location) {
            // Slug frissítés
            if ($location->isDirty('name') && empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
            
            // Koordináták automatikus frissítése, ha a város vagy cím változott és nincsenek koordináták
            if ($location->isDirty(['city', 'address']) && (empty($location->latitude) || empty($location->longitude))) {
                static::geocodeLocation($location);
            }
        });
    }

    /**
     * Geocoding - koordináták lekérése cím/város alapján
     */
    protected static function geocodeLocation($location)
    {
        $city = $location->city ?: $location->name;
        $address = $location->address;
        
        try {
            // Először próbáljuk a teljes címmel
            $fullAddress = $address ? "{$address}, {$city}, Hungary" : "{$city}, Hungary";
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($fullAddress) . '&limit=1';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'BetonPluss/1.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $data = json_decode($response, true);
                
                // Ha nincs találat és volt cím, próbáljuk meg csak a várossal
                if ((!$data || count($data) === 0) && $address) {
                    sleep(1); // Rate limiting
                    $cityOnly = "{$city}, Hungary";
                    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($cityOnly) . '&limit=1';
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'BetonPluss/1.0');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    
                    if ($response) {
                        $data = json_decode($response, true);
                    }
                }
                
                if ($data && count($data) > 0) {
                    $location->latitude = round($data[0]['lat'], 8);
                    $location->longitude = round($data[0]['lon'], 8);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Geocoding error for location: ' . $location->name, ['error' => $e->getMessage()]);
        }
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'location_product')
            ->withPivot('gross_price', 'net_price', 'is_available')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
