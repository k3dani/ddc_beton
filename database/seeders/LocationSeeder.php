<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Budapest',
                'slug' => 'budapest',
                'latitude' => 47.495314332917474,
                'longitude' => 19.04096286943015,
                'is_active' => true,
            ],
            [
                'name' => 'Székesfehérvár',
                'slug' => 'szekesfehervar',
                'latitude' => 47.175372574381655,
                'longitude' => 18.421680677923305,
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['slug' => $location['slug']],
                $location
            );
        }
    }
}
