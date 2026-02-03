<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\DeliveryPrice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user létrehozása
        User::firstOrCreate(
            ['email' => 'admin@dunaconcrete.hu'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

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
            Location::updateOrCreate(
                ['slug' => $locationData['slug'] ?? \Illuminate\Support\Str::slug($locationData['name'])],
                $locationData
            );
        }

        // Kategóriák
        $categories = [
            ['name' => 'Pillérbeton', 'slug' => 'pillerbeton', 'description' => 'Beton pillérekhez és oszlopokhoz', 'sort_order' => 1, 'is_visible_on_house' => true, 'position_x' => '25%', 'position_y' => '15%'],
            ['name' => 'Födémbeton', 'slug' => 'fodembeton', 'description' => 'Födémek betonozásához', 'sort_order' => 2, 'is_visible_on_house' => true, 'position_x' => '48%', 'position_y' => '7%'],
            ['name' => 'Lépcsőbeton', 'slug' => 'lepcsobeton', 'description' => 'Lépcsők készítéséhez', 'sort_order' => 3, 'is_visible_on_house' => true, 'position_x' => '72%', 'position_y' => '28%'],
            ['name' => 'Aljzatbeton', 'slug' => 'aljzatbeton', 'description' => 'Padlóaljzatok készítéséhez', 'sort_order' => 4, 'is_visible_on_house' => true, 'position_x' => '35%', 'position_y' => '65%'],
            ['name' => 'Falazatbeton', 'slug' => 'falazatbeton', 'description' => 'Falazatokhoz és kerítésekhez', 'sort_order' => 5, 'is_visible_on_house' => true, 'position_x' => '15%', 'position_y' => '45%'],
            ['name' => 'Alapbeton', 'slug' => 'alapbeton', 'description' => 'Építmények alapozásához', 'sort_order' => 6, 'is_visible_on_house' => true, 'position_x' => '60%', 'position_y' => '72%'],
            ['name' => 'Járdabeton', 'slug' => 'jardabeton', 'description' => 'Járdák és bejárók készítéséhez', 'sort_order' => 7, 'is_visible_on_house' => true, 'position_x' => '80%', 'position_y' => '85%'],
            ['name' => 'Medencebeton', 'slug' => 'medencebeton', 'description' => 'Úszómedencék és vizesedények betonozásához', 'sort_order' => 8, 'is_visible_on_house' => false],
            ['name' => 'Betonblokkok', 'slug' => 'betonblokkok', 'description' => 'ECOBlock és egyéb blokkok', 'sort_order' => 9, 'is_visible_on_house' => false],
            ['name' => 'Speciális termékek', 'slug' => 'specialis', 'description' => 'EcoCrete és speciális megoldások', 'sort_order' => 10, 'is_visible_on_house' => false],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Termékek
        $pillerCat = ProductCategory::where('slug', 'pillerbeton')->first();
        $fodemCat = ProductCategory::where('slug', 'fodembeton')->first();
        $lepcsCat = ProductCategory::where('slug', 'lepcsobeton')->first();
        $aljzatCat = ProductCategory::where('slug', 'aljzatbeton')->first();
        $falazatCat = ProductCategory::where('slug', 'falazatbeton')->first();
        $alapCat = ProductCategory::where('slug', 'alapbeton')->first();
        $jardaCat = ProductCategory::where('slug', 'jardabeton')->first();
        $medenceCat = ProductCategory::where('slug', 'medencebeton')->first();
        $blokkCat = ProductCategory::where('slug', 'betonblokkok')->first();
        $specialCat = ProductCategory::where('slug', 'specialis')->first();

        $products = [
            [
                'name' => 'Pillérbeton C25/30',
                'technical_name' => 'C25/30 F2',
                'slug' => 'pillerbeton-c25-30-f2',
                'description' => 'Pillérbeton családi házakhoz, C25/30 szilárdsági osztály, F2 fagytállóság.',
                'short_description' => 'Pillérbeton családi házakhoz',
                'sku' => 'BET-C25-F2',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 1,
                'categories' => [$pillerCat->id],
            ],
            [
                'name' => 'Födémbeton C20/25',
                'technical_name' => 'C20/25 F2',
                'slug' => 'fodembeton-c20-25-f2',
                'description' => 'Födémbetonozáshoz optimális C20/25 szilárdsági osztály.',
                'short_description' => 'Födémek betonozásához',
                'sku' => 'BET-C20-F2',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 2,
                'categories' => [$fodemCat->id],
            ],
            [
                'name' => 'Lépcső beton C30/37',
                'technical_name' => 'C30/37 F3',
                'slug' => 'lepcsobeton-c30-37-f3',
                'description' => 'Nagy szilárdságú beton lépcsőkhöz, C30/37 szilárdsági osztály.',
                'short_description' => 'Lépcsők készítéséhez',
                'sku' => 'BET-C30-F3',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 3,
                'categories' => [$lepcsCat->id],
            ],
            [
                'name' => 'Aljzatbeton C16/20',
                'technical_name' => 'C16/20 F1',
                'slug' => 'aljzatbeton-c16-20-f1',
                'description' => 'Padlóaljzat készítéséhez megfelelő C16/20 beton.',
                'short_description' => 'Padlóaljzatok készítéséhez',
                'sku' => 'BET-C16-F1',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 4,
                'categories' => [$aljzatCat->id],
            ],
            [
                'name' => 'Falazatbeton C20/25',
                'technical_name' => 'C20/25 F2',
                'slug' => 'falazatbeton-c20-25-f2',
                'description' => 'Falazatok és kerítések készítéséhez ideális C20/25 beton.',
                'short_description' => 'Falazatokhoz és kerítésekhez',
                'sku' => 'BET-FAL-C20',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 5,
                'categories' => [$falazatCat->id],
            ],
            [
                'name' => 'Alapbeton C16/20',
                'technical_name' => 'C16/20 F2',
                'slug' => 'alapbeton-c16-20-f2',
                'description' => 'Építmények alapozásához használható C16/20 beton.',
                'short_description' => 'Építmények alapozásához',
                'sku' => 'BET-ALP-C16',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 6,
                'categories' => [$alapCat->id],
            ],
            [
                'name' => 'Járdabeton C12/15',
                'technical_name' => 'C12/15 F1',
                'slug' => 'jardabeton-c12-15-f1',
                'description' => 'Járdák és bejárók készítéséhez megfelelő C12/15 beton.',
                'short_description' => 'Járdák és bejárók készítéséhez',
                'sku' => 'BET-JAR-C12',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 7,
                'categories' => [$jardaCat->id],
            ],
            [
                'name' => 'Medencebeton C25/30',
                'technical_name' => 'C25/30 F3 Vízzáró',
                'slug' => 'medencebeton-c25-30-f3',
                'description' => 'Vízzáró medencebeton C25/30 szilárdsági osztállyal, F3 fagytállósággal.',
                'short_description' => 'Úszómedencék készítéséhez',
                'sku' => 'BET-MED-C25',
                'unit' => 'm³',
                'volume_cbm' => 1.0,
                'is_active' => true,
                'sort_order' => 8,
                'categories' => [$medenceCat->id],
            ],
            [
                'name' => 'ECOBlock Kicsi',
                'technical_name' => '500x600x600 mm',
                'slug' => 'ecoblock-500',
                'description' => 'Újrahasznosított betonblokk 500x600x600 mm',
                'short_description' => 'ECOBlock kicsi méret',
                'sku' => 'ECO-500',
                'unit' => 'db',
                'volume_cbm' => 0.18,
                'is_active' => true,
                'sort_order' => 9,
                'categories' => [$blokkCat->id, $specialCat->id],
            ],
            [
                'name' => 'ECOBlock Közepes',
                'technical_name' => '900x600x600 mm',
                'slug' => 'ecoblock-900',
                'description' => 'Újrahasznosított betonblokk 900x600x600 mm',
                'short_description' => 'ECOBlock közepes méret',
                'sku' => 'ECO-900',
                'unit' => 'db',
                'volume_cbm' => 0.324,
                'is_active' => true,
                'sort_order' => 10,
                'categories' => [$blokkCat->id, $specialCat->id],
            ],
        ];

        $productModels = [];
        foreach ($products as $productData) {
            $categoryIds = $productData['categories'];
            unset($productData['categories']);
            
            $product = Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
            $product->categories()->sync($categoryIds);
            
            $productModels[] = $product;
        }

        // Árak beállítása
        $vac = Location::where('city', 'Vác')->first();
        $bp = Location::where('city', 'Budapest')->first();
        $gyor = Location::where('city', 'Győr')->first();

        // Pillérbeton
        $productModels[0]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 28500, 'net_price' => 22440, 'is_available' => true]]);
        $productModels[0]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 29500, 'net_price' => 23228, 'is_available' => true]]);
        $productModels[0]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 28000, 'net_price' => 22047, 'is_available' => true]]);
        
        // Födémbeton
        $productModels[1]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 26500, 'net_price' => 20866, 'is_available' => true]]);
        $productModels[1]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 27500, 'net_price' => 21654, 'is_available' => true]]);
        
        // Lépcsőbeton
        $productModels[2]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 32000, 'net_price' => 25197, 'is_available' => true]]);
        $productModels[2]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 31500, 'net_price' => 24803, 'is_available' => true]]);
        
        // Aljzatbeton
        $productModels[3]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 22000, 'net_price' => 17323, 'is_available' => true]]);
        $productModels[3]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 23000, 'net_price' => 18110, 'is_available' => true]]);
        $productModels[3]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 22500, 'net_price' => 17717, 'is_available' => true]]);
        
        // Falazatbeton
        $productModels[4]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 25000, 'net_price' => 19685, 'is_available' => true]]);
        $productModels[4]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 26000, 'net_price' => 20472, 'is_available' => true]]);
        
        // Alapbeton
        $productModels[5]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 23000, 'net_price' => 18110, 'is_available' => true]]);
        $productModels[5]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 22800, 'net_price' => 17953, 'is_available' => true]]);
        
        // Járdabeton
        $productModels[6]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 20000, 'net_price' => 15748, 'is_available' => true]]);
        $productModels[6]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 21000, 'net_price' => 16535, 'is_available' => true]]);
        $productModels[6]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 20500, 'net_price' => 16142, 'is_available' => true]]);
        
        // Medencebeton (csak Vác-on)
        $productModels[7]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 35000, 'net_price' => 27559, 'is_available' => true]]);
        
        // ECOBlock Kicsi
        $productModels[8]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 12000, 'net_price' => 9449, 'is_available' => true]]);
        $productModels[8]->locations()->syncWithoutDetaching([$bp->id => ['gross_price' => 12500, 'net_price' => 9843, 'is_available' => true]]);
        
        // ECOBlock Közepes
        $productModels[9]->locations()->syncWithoutDetaching([$vac->id => ['gross_price' => 18000, 'net_price' => 14173, 'is_available' => true]]);
        $productModels[9]->locations()->syncWithoutDetaching([$gyor->id => ['gross_price' => 19000, 'net_price' => 14961, 'is_available' => true]]);

        // Fuvar árak beállítása
        // Vác telephely
        DeliveryPrice::updateOrCreate(
            ['location_id' => $vac->id, 'distance_from_km' => 0, 'distance_to_km' => 5],
            ['price_per_cbm' => 5000]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $vac->id, 'distance_from_km' => 6, 'distance_to_km' => 10],
            ['price_per_cbm' => 6000]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $vac->id, 'distance_from_km' => 11, 'distance_to_km' => 20],
            ['price_per_cbm' => 7500]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $vac->id, 'distance_from_km' => 21, 'distance_to_km' => 30],
            ['price_per_cbm' => 9000]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $vac->id, 'distance_from_km' => 31, 'distance_to_km' => 50],
            ['price_per_cbm' => 11000]
        );

        // Budapest telephely
        DeliveryPrice::updateOrCreate(
            ['location_id' => $bp->id, 'distance_from_km' => 0, 'distance_to_km' => 5],
            ['price_per_cbm' => 5500]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $bp->id, 'distance_from_km' => 6, 'distance_to_km' => 10],
            ['price_per_cbm' => 6500]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $bp->id, 'distance_from_km' => 11, 'distance_to_km' => 20],
            ['price_per_cbm' => 8000]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $bp->id, 'distance_from_km' => 21, 'distance_to_km' => 30],
            ['price_per_cbm' => 9500]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $bp->id, 'distance_from_km' => 31, 'distance_to_km' => 50],
            ['price_per_cbm' => 12000]
        );

        // Győr telephely
        DeliveryPrice::updateOrCreate(
            ['location_id' => $gyor->id, 'distance_from_km' => 0, 'distance_to_km' => 5],
            ['price_per_cbm' => 4800]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $gyor->id, 'distance_from_km' => 6, 'distance_to_km' => 10],
            ['price_per_cbm' => 5800]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $gyor->id, 'distance_from_km' => 11, 'distance_to_km' => 20],
            ['price_per_cbm' => 7200]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $gyor->id, 'distance_from_km' => 21, 'distance_to_km' => 30],
            ['price_per_cbm' => 8500]
        );
        DeliveryPrice::updateOrCreate(
            ['location_id' => $gyor->id, 'distance_from_km' => 31, 'distance_to_km' => 50],
            ['price_per_cbm' => 10500]
        );
    }
}

