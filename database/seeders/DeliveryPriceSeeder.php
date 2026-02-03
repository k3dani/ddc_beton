<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\DeliveryPrice;

class DeliveryPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all locations
        $locations = Location::all();

        // Define distance bands (0-5, 6-10, 11-15, ..., 76-80)
        $distanceBands = [
            ['from' => 0, 'to' => 5, 'price' => 5000],
            ['from' => 6, 'to' => 10, 'price' => 6000],
            ['from' => 11, 'to' => 15, 'price' => 7000],
            ['from' => 16, 'to' => 20, 'price' => 8000],
            ['from' => 21, 'to' => 25, 'price' => 9000],
            ['from' => 26, 'to' => 30, 'price' => 10000],
            ['from' => 31, 'to' => 35, 'price' => 11000],
            ['from' => 36, 'to' => 40, 'price' => 12000],
            ['from' => 41, 'to' => 45, 'price' => 13000],
            ['from' => 46, 'to' => 50, 'price' => 14000],
            ['from' => 51, 'to' => 55, 'price' => 15000],
            ['from' => 56, 'to' => 60, 'price' => 16000],
            ['from' => 61, 'to' => 65, 'price' => 17000],
            ['from' => 66, 'to' => 70, 'price' => 18000],
            ['from' => 71, 'to' => 75, 'price' => 19000],
            ['from' => 76, 'to' => 80, 'price' => 20000],
        ];

        // Create delivery prices for each location
        foreach ($locations as $location) {
            foreach ($distanceBands as $band) {
                DeliveryPrice::create([
                    'location_id' => $location->id,
                    'distance_from_km' => $band['from'],
                    'distance_to_km' => $band['to'],
                    'price_per_cbm' => $band['price'],
                ]);
            }
        }

        $this->command->info('Delivery prices seeded successfully!');
    }
}
