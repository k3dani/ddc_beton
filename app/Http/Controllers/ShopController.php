<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Location;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Ellenőrizzük, hogy ki van-e választva telephely
     */
    private function checkLocationSelected()
    {
        if (!session('selected_location_id')) {
            return redirect()->route('homepage')
                ->with('error', 'Kérjük, először válasszon telephelyet a térképről!');
        }
        return null;
    }

    public function index(Request $request)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;
        
        $locations = Location::active()->orderBy('name')->get();
        
        // Kiválasztott telephely
        $selectedLocationId = $request->session()->get('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;
        
        // Kategóriák betöltése termékekkel
        $allCategories = ProductCategory::with(['products' => function($q) use ($selectedLocation) {
            $q->active()->ordered();
            
            // Ha van kiválasztott telephely, csak azokat a termékeket, amelyekhez van ár
            if ($selectedLocation) {
                $q->whereHas('locations', function($query) use ($selectedLocation) {
                    $query->where('location_id', $selectedLocation->id)
                          ->where('is_available', true)
                          ->whereNotNull('gross_price')
                          ->whereNotNull('net_price');
                })
                ->with(['locations' => function($query) use ($selectedLocation) {
                    $query->where('location_id', $selectedLocation->id);
                }]);
            }
        }])->ordered()->get();
        
        // Szétválasztjuk a házas képen megjelenőket és az alul megjelenőket
        $categoriesOnHouse = $allCategories->where('is_visible_on_house', true);
        $categoriesBelowHouse = $allCategories->where('is_visible_on_house', false);
        
        $query = Product::with(['categories'])->active()->ordered();
        
        // Ha van kiválasztott telephely, csak azokat a termékeket jelenítjük meg, amelyekhez van ár
        if ($selectedLocation) {
            $query->whereHas('locations', function($q) use ($selectedLocation) {
                $q->where('location_id', $selectedLocation->id)
                  ->where('is_available', true)
                  ->whereNotNull('gross_price')
                  ->whereNotNull('net_price');
            });
            
            // Betöltjük az árakat is
            $query->with(['locations' => function($q) use ($selectedLocation) {
                $q->where('location_id', $selectedLocation->id);
            }]);
        }
        
        // Kategória szűrés
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('product_categories.id', $request->category);
            });
        }
        
        $products = $query->get();

        return view('pages.shop', compact('products', 'allCategories', 'categoriesOnHouse', 'categoriesBelowHouse', 'locations', 'selectedLocation'));
    }

    public function category($categorySlug)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;

        $category = ProductCategory::where('slug', $categorySlug)->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();
        
        // Kiválasztott telephely
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;
        
        // Kategóriák betöltése termékekkel
        $allCategories = ProductCategory::with(['products' => function($q) use ($selectedLocation) {
            $q->active()->ordered();
            
            if ($selectedLocation) {
                $q->whereHas('locations', function($query) use ($selectedLocation) {
                    $query->where('location_id', $selectedLocation->id)
                          ->where('is_available', true)
                          ->whereNotNull('gross_price')
                          ->whereNotNull('net_price');
                })
                ->with(['locations' => function($query) use ($selectedLocation) {
                    $query->where('location_id', $selectedLocation->id);
                }]);
            }
        }])->ordered()->get();
        
        // Szétválasztjuk a házas képen megjelenőket és az alul megjelenőket
        $categoriesOnHouse = $allCategories->where('is_visible_on_house', true);
        $categoriesBelowHouse = $allCategories->where('is_visible_on_house', false);
        
        $query = Product::whereHas('categories', function($q) use ($category) {
                $q->where('product_categories.id', $category->id);
            })
            ->with(['categories'])
            ->active()
            ->ordered();
        
        // Ha van kiválasztott telephely, csak azokat a termékeket jelenítjük meg, amelyekhez van ár
        if ($selectedLocation) {
            $query->whereHas('locations', function($q) use ($selectedLocation) {
                $q->where('location_id', $selectedLocation->id)
                  ->where('is_available', true)
                  ->whereNotNull('gross_price')
                  ->whereNotNull('net_price');
            });
            
            // Betöltjük az árakat is
            $query->with(['locations' => function($q) use ($selectedLocation) {
                $q->where('location_id', $selectedLocation->id);
            }]);
        }
        
        $products = $query->get();

        return view('pages.shop', compact('products', 'allCategories', 'categoriesOnHouse', 'categoriesBelowHouse', 'locations', 'selectedLocation', 'category'));
    }

    public function show($slug)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;

        $product = Product::where('slug', $slug)->with(['categories', 'locations'])->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();
        
        // Kiválasztott telephely
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;

        return view('pages.product-detail', compact('product', 'locations', 'selectedLocation'));
    }

    /**
     * Termék kosárba helyezése
     */
    public function addToCart(Request $request, $slug)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;

        $request->validate([
            'quantity' => 'required|numeric|min:0.5',
            'location_id' => 'required|exists:locations,id'
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Ellenőrizzük, hogy a termék elérhető-e a kiválasztott telephelyen
        $locationProduct = $product->locations()
            ->where('location_id', $request->location_id)
            ->where('is_available', true)
            ->first();

        if (!$locationProduct) {
            return back()->with('error', 'Ez a termék nem elérhető a kiválasztott telephelyen.');
        }

        // Kosár kezelése session-ben
        $cart = session()->get('cart', []);
        
        // Ha a kosár üres volt, töröljük a régi pumpa és fuvar adatokat
        // (új rendelés indul)
        if (empty($cart)) {
            session()->forget([
                'needs_delivery',
                'pump_id',
                'pump_type',
                'pump_boom_length',
                'pump_fixed_fee',
                'pump_hourly_fee',
                'pump_estimated_hours',
            ]);
        }
        
        $cartKey = $product->id . '_' . $request->location_id;
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'location_id' => $request->location_id,
                'quantity' => $request->quantity,
                'unit' => $product->unit,
                'gross_price' => $locationProduct->pivot->gross_price,
                'net_price' => $locationProduct->pivot->net_price,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'A termék sikeresen hozzáadva a kosárhoz!',
                'cart_count' => count($cart)
            ]);
        }

        return back()->with('success', 'A termék sikeresen hozzáadva a kosárhoz!');
    }
}
