<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'type',
        'boom_length',
        'fixed_fee',
        'hourly_fee',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
