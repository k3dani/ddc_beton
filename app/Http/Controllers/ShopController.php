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
        $categories = ProductCategory::ordered()->get();
        $locations = Location::active()->orderBy('name')->get();
        
        // Kiválasztott telephely
        $selectedLocationId = $request->session()->get('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;
        
        $query = Product::with(['category'])->active()->ordered();
        
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
            $query->where('product_category_id', $request->category);
        }
        
        $products = $query->get();

        return view('pages.shop', compact('products', 'categories', 'locations', 'selectedLocation'));
    }

    public function category($categorySlug)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;

        $category = ProductCategory::where('slug', $categorySlug)->firstOrFail();
        $categories = ProductCategory::ordered()->get();
        $locations = Location::active()->orderBy('name')->get();
        
        // Kiválasztott telephely
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;
        
        $query = Product::where('product_category_id', $category->id)
            ->with(['category'])
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

        return view('pages.shop', compact('products', 'categories', 'locations', 'selectedLocation', 'category'));
    }

    public function show($slug)
    {
        // Telephely ellenőrzés
        $redirect = $this->checkLocationSelected();
        if ($redirect) return $redirect;

        $product = Product::where('slug', $slug)->with(['category', 'locations'])->firstOrFail();
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
