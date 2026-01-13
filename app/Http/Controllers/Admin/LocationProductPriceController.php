<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationProductPriceController extends Controller
{
    public function index()
    {
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        
        // Árak mátrixának előkészítése
        $prices = [];
        foreach ($products as $product) {
            foreach ($locations as $location) {
                $pivot = DB::table('location_product')
                    ->where('location_id', $location->id)
                    ->where('product_id', $product->id)
                    ->first();
                
                $prices[$product->id][$location->id] = $pivot ? [
                    'gross_price' => $pivot->gross_price,
                    'net_price' => $pivot->net_price,
                    'is_available' => $pivot->is_available,
                ] : null;
            }
        }
        
        return view('admin.location-product-prices.index', compact('locations', 'products', 'prices'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*.location_id' => 'required|exists:locations,id',
            'prices.*.product_id' => 'required|exists:products,id',
            'prices.*.gross_price' => 'nullable|numeric|min:0',
            'prices.*.net_price' => 'nullable|numeric|min:0',
            'prices.*.is_available' => 'boolean',
        ]);
        
        DB::beginTransaction();
        try {
            foreach ($request->prices as $priceData) {
                $locationId = $priceData['location_id'];
                $productId = $priceData['product_id'];
                $grossPrice = $priceData['gross_price'] ?? null;
                $netPrice = $priceData['net_price'] ?? null;
                $isAvailable = $priceData['is_available'] ?? false;
                
                // Ha van ár megadva
                if ($grossPrice !== null && $netPrice !== null) {
                    DB::table('location_product')->updateOrInsert(
                        [
                            'location_id' => $locationId,
                            'product_id' => $productId,
                        ],
                        [
                            'gross_price' => $grossPrice,
                            'net_price' => $netPrice,
                            'is_available' => $isAvailable,
                            'updated_at' => now(),
                        ]
                    );
                } else {
                    // Ha nincs ár, töröljük
                    DB::table('location_product')
                        ->where('location_id', $locationId)
                        ->where('product_id', $productId)
                        ->delete();
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.location-product-prices.index')
                ->with('success', 'Árak sikeresen frissítve!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Hiba történt: ' . $e->getMessage())
                ->withInput();
        }
    }
}
