<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user létrehozása
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dunaconcrete.hu',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Telephelyek
        $locations = [
            [
                'name' => 'Vác Betongyár',
                'city' => 'Vác',
                'address' => '2600 Vác, Köztársaság út 52.',
                'latitude' => 47.7768,
                'longitude' => 19.1393,
                'phone' => '+36 27 511 080',
                'email' => 'vac@dunaconcrete.hu',
                'is_active' => true,
            ],
            [
                'name' => 'Budapest Telephely',
                'city' => 'Budapest',
                'address' => '1116 Budapest, Fehérvári út 123.',
                'latitude' => 47.4626,
                'longitude' => 19.0396,
                'phone' => '+36 1 234 5678',
                'email' => 'budapest@dunaconcrete.hu',
                'is_active' => true,
            ],
            [
                'name' => 'Győr Betonüzem',
                'city' => 'Győr',
                'address' => '9024 Győr, Ipari út 45.',
                'latitude' => 47.6875,
                'longitude' => 17.6504,
                'phone' => '+36 96 123 456',
                'email' => 'gyor@dunaconcrete.hu',
                'is_active' => true,
            ],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        // Kategóriák
        $categories = [
            ['name' => 'Beton', 'slug' => 'beton', 'description' => 'Különböző minőségű betonok', 'sort_order' => 1],
            ['name' => 'Betonblokkok', 'slug' => 'betonblokkok', 'description' => 'ECOBlock és egyéb blokkok', 'sort_order' => 2],
            ['name' => 'Speciális termékek', 'slug' => 'specialis', 'description' => 'EcoCrete és speciális megoldások', 'sort_order' => 3],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::create($categoryData);
        }

        // Termékek
        $betonCat = ProductCategory::where('slug', 'beton')->first();
        $blokkCat = ProductCategory::where('slug', 'betonblokkok')->first();
        $specialCat = ProductCategory::where('slug', 'specialis')->first();

        $products = [
            [
                'product_category_id' => $betonCat->id,
                'name' => 'Pillérbeton C25/30 F2',
                'slug' => 'pillerbeton-c25-30-f2',
                'description' => 'Pillérbeton családi házakhoz, C25/30 szilárdsági osztály, F2 fagytállóság.',
                'short_description' => 'Pillérbeton családi házakhoz',
                'sku' => 'BET-C25-F2',
                'unit' => 'm³',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'product_category_id' => $blokkCat->id,
                'name' => 'ECOBlock 500x600x600',
                'slug' => 'ecoblock-500',
                'description' => 'Újrahasznosított betonblokk 500x600x600 mm',
                'short_description' => 'ECOBlock kicsi méret',
                'sku' => 'ECO-500',
                'unit' => 'db',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'product_category_id' => $blokkCat->id,
                'name' => 'ECOBlock 900x600x600',
                'slug' => 'ecoblock-900',
                'description' => 'Újrahasznosított betonblokk 900x600x600 mm',
                'short_description' => 'ECOBlock közepes méret',
                'sku' => 'ECO-900',
                'unit' => 'db',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        $productModels = [];
        foreach ($products as $productData) {
            $productModels[] = Product::create($productData);
        }

        // Árak beállítása
        $vac = Location::where('city', 'Vác')->first();
        $bp = Location::where('city', 'Budapest')->first();
        $gyor = Location::where('city', 'Győr')->first();

        $productModels[0]->locations()->attach($vac->id, ['price' => 28500, 'currency' => 'HUF', 'is_available' => true]);
        $productModels[0]->locations()->attach($bp->id, ['price' => 29500, 'currency' => 'HUF', 'is_available' => true]);
        
        $productModels[1]->locations()->attach($vac->id, ['price' => 12000, 'currency' => 'HUF', 'is_available' => true]);
        $productModels[1]->locations()->attach($bp->id, ['price' => 12500, 'currency' => 'HUF', 'is_available' => true]);
        
        $productModels[2]->locations()->attach($vac->id, ['price' => 18000, 'currency' => 'HUF', 'is_available' => true]);
        $productModels[2]->locations()->attach($gyor->id, ['price' => 19000, 'currency' => 'HUF', 'is_available' => true]);
    }
}

