<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Product;
use App\Models\DeliveryPrice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pump;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        \Log::info('Checkout page loaded', [
            'cart_count' => count($cart),
            'cart_keys' => array_keys($cart),
            'cart_items' => $cart
        ]);
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'A kosár üres!');
        }

        // Kiválasztott telephely
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;

        // Összesítés számítása
        $total = 0;
        $totalVolume = 0;
        
        foreach ($cart as $item) {
            $total += $item['gross_price'] * $item['quantity'];
            
            // Calculate total volume
            if (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product && $product->volume_cbm) {
                    $totalVolume += $product->volume_cbm * $item['quantity'];
                }
            }
        }

        // Get delivery information from session
        $needsDelivery = session('needs_delivery', false);
        $constructionDistanceKm = session('construction_distance_km');
        $constructionAddress = session('construction_address');
        $deliveryPrice = null;

        // Calculate delivery price if user requested delivery
        if ($needsDelivery && $selectedLocationId && $constructionDistanceKm && $totalVolume > 0) {
            $deliveryPrice = $this->calculateDeliveryPrice($selectedLocationId, $constructionDistanceKm, $totalVolume);
        }

        // Get pump information from session
        $pumpId = session('pump_id');
        $pumpType = session('pump_type');
        $pumpBoomLength = session('pump_boom_length');
        $pumpFixedFee = session('pump_fixed_fee');
        $pumpHourlyFee = session('pump_hourly_fee');
        $pumpEstimatedHours = session('pump_estimated_hours');

        // Add pump fees to total if pump is selected
        if ($pumpFixedFee) {
            $total += $pumpFixedFee;
        }
        if ($pumpHourlyFee && $pumpEstimatedHours) {
            $total += $pumpHourlyFee * $pumpEstimatedHours;
        }

        return view('pages.checkout', compact(
            'cart', 
            'selectedLocation', 
            'total',
            'totalVolume',
            'constructionDistanceKm',
            'constructionAddress',
            'deliveryPrice',
            'pumpId',
            'pumpType',
            'pumpBoomLength',
            'pumpFixedFee',
            'pumpHourlyFee',
            'pumpEstimatedHours'
        ));
    }

    public function store(Request $request)
    {
        // Validate basic fields
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            
            // Company fields
            'is_company' => 'nullable|boolean',
            'company_name' => 'required_if:is_company,1|nullable|string|max:255',
            'tax_number' => 'required_if:is_company,1|nullable|string|max:50',
            
            // Billing address fields
            'billing_postal_code' => 'required|string|max:10',
            'billing_city' => 'required|string|max:255',
            'billing_street' => 'required|string|max:255',
            'billing_house_number' => 'required|string|max:20',
            
            // Delivery address fields
            'delivery_postal_code' => 'required|string|max:10',
            'delivery_city' => 'required|string|max:255',
            'delivery_street' => 'required|string|max:255',
            'delivery_house_number' => 'required|string|max:20',
        ];

        $request->validate($rules);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'A kosár üres!');
        }

        DB::beginTransaction();
        
        try {
            // Calculate subtotal and total volume
            $subtotal = 0;
            $totalVolume = 0;
            
            foreach ($cart as $item) {
                $subtotal += $item['gross_price'] * $item['quantity'];
                
                if (isset($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->volume_cbm) {
                        $totalVolume += $product->volume_cbm * $item['quantity'];
                    }
                }
            }

            // Get delivery choice from session
            $needsDelivery = session('needs_delivery', false);
            $deliveryPrice = null;
            $deliveryDistanceKm = null;
            $deliveryVolumeCbm = null;
            
            if ($needsDelivery) {
                $selectedLocationId = session('selected_location_id');
                $constructionDistanceKm = session('construction_distance_km');
                
                if ($selectedLocationId && $constructionDistanceKm && $totalVolume > 0) {
                    $deliveryPrice = $this->calculateDeliveryPrice($selectedLocationId, $constructionDistanceKm, $totalVolume);
                    $deliveryDistanceKm = $constructionDistanceKm;
                    $deliveryVolumeCbm = $totalVolume;
                }
            }

            $total = $subtotal + ($deliveryPrice ?? 0);

            // Get pump choice from session
            $pumpId = session('pump_id');
            $pumpType = session('pump_type');
            $pumpFixedFee = session('pump_fixed_fee');
            $pumpHourlyFee = session('pump_hourly_fee');
            $pumpEstimatedHours = session('pump_estimated_hours');
            
            // Add pump fees to total
            if ($pumpFixedFee) {
                $total += $pumpFixedFee;
            }
            if ($pumpHourlyFee && $pumpEstimatedHours) {
                $total += $pumpHourlyFee * $pumpEstimatedHours;
            }

            // Create order
            $order = Order::create([
                'location_id' => session('selected_location_id'),
                'is_company' => $request->has('is_company') && $request->is_company == '1',
                'company_name' => $request->company_name,
                'tax_number' => $request->tax_number,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->billing_postal_code . ' ' . $request->billing_city . ', ' . 
                            $request->billing_street . ' ' . $request->billing_house_number,
                'billing_postal_code' => $request->billing_postal_code,
                'billing_city' => $request->billing_city,
                'billing_street' => $request->billing_street,
                'billing_house_number' => $request->billing_house_number,
                'delivery_address' => $request->delivery_postal_code . ' ' . $request->delivery_city . ', ' . 
                                     $request->delivery_street . ' ' . $request->delivery_house_number,
                'delivery_postal_code' => $request->delivery_postal_code,
                'delivery_city' => $request->delivery_city,
                'delivery_street' => $request->delivery_street,
                'delivery_house_number' => $request->delivery_house_number,
                'notes' => $request->notes,
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => 'pending',
                'needs_delivery' => $needsDelivery,
                'delivery_distance_km' => $deliveryDistanceKm,
                'delivery_volume_cbm' => $deliveryVolumeCbm,
                'delivery_price' => $deliveryPrice,
                'pump_id' => $pumpId,
                'pump_type' => $pumpType,
                'pump_fixed_fee' => $pumpFixedFee,
                'pump_hourly_fee' => $pumpHourlyFee,
                'pump_estimated_hours' => $pumpEstimatedHours,
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['sku'] ?? null,
                    'unit' => $item['unit'] ?? 'm³',
                    'quantity' => $item['quantity'],
                    'price' => $item['gross_price'],
                    'total' => $item['gross_price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            \Log::info('Új rendelés létrehozva', [
                'order_id' => $order->id,
                'customer' => $request->only(['name', 'email', 'phone']),
                'total' => $total,
                'needs_delivery' => $needsDelivery,
                'delivery_price' => $deliveryPrice,
            ]);

            // Kosár ürítése és delivery + pump session tisztítása
            session()->forget([
                'cart', 
                'needs_delivery', 
                'delivery_volume_cbm', 
                'delivery_distance_km', 
                'delivery_price_calculated',
                'pump_id',
                'pump_type',
                'pump_boom_length',
                'pump_fixed_fee',
                'pump_hourly_fee',
                'pump_estimated_hours'
            ]);
            session()->save();

            return redirect()->route('checkout.success');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Rendelés létrehozása sikertelen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hiba történt a rendelés feldolgozása során. Kérjük, próbálja újra.');
        }
    }

    public function success()
    {
        // Biztosítjuk hogy a kosár üres
        if (session()->has('cart')) {
            session()->forget('cart');
            session()->save();
        }
        
        return view('pages.checkout-success');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['gross_price'] * $item['quantity'];
        }

        return view('pages.cart', compact('cart', 'total'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart')->with('success', 'A kosár törölve!');
    }

    public function updateCart(Request $request)
    {
        \Log::info('Update cart request', [
            'all' => $request->all(),
            'json' => $request->json()->all(),
        ]);

        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|numeric|min:0.5',
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->input('cart_key');

        if (!isset($cart[$cartKey])) {
            return response()->json(['success' => false, 'message' => 'Tétel nem található'], 404);
        }

        // Frissítjük a mennyiséget
        $cart[$cartKey]['quantity'] = $request->input('quantity');
        session()->put('cart', $cart);

        // Számítások
        $itemTotal = $cart[$cartKey]['gross_price'] * $cart[$cartKey]['quantity'];
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += $item['gross_price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'item_total' => $itemTotal,
            'grand_total' => $grandTotal,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        \Log::info('Remove from cart request', [
            'all' => $request->all(),
            'json' => $request->json()->all(),
            'input' => $request->input('cart_key')
        ]);

        $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->input('cart_key');

        if (!isset($cart[$cartKey])) {
            return response()->json(['success' => false, 'message' => 'Tétel nem található'], 404);
        }

        // Töröljük a tételt
        unset($cart[$cartKey]);
        session()->put('cart', $cart);

        // Ha a kosár üres lett, töröljük a fuvar és pumpa adatokat is
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

        // Végösszeg számítása
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += $item['gross_price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'grand_total' => $grandTotal,
            'cart_empty' => empty($cart),
        ]);
    }

    /**
     * Calculate delivery price based on location, distance and volume
     */
    private function calculateDeliveryPrice($locationId, $distanceKm, $volumeCbm)
    {
        if (!$locationId || !$distanceKm || !$volumeCbm) {
            return null;
        }

        $deliveryPrice = DeliveryPrice::where('location_id', $locationId)
            ->where('distance_from_km', '<=', $distanceKm)
            ->where('distance_to_km', '>=', $distanceKm)
            ->first();

        if (!$deliveryPrice) {
            return null;
        }

        return $deliveryPrice->price_per_cbm * $volumeCbm;
    }

    /**
     * Calculate delivery price for modal (AJAX endpoint)
     */
    public function calculateDelivery(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'A kosár üres.'
            ]);
        }

        $selectedLocationId = session('selected_location_id');
        $constructionDistanceKm = session('construction_distance_km');

        if (!$selectedLocationId) {
            return response()->json([
                'success' => false,
                'message' => 'Kérjük, válasszon telephelyet!'
            ]);
        }

        if (!$constructionDistanceKm) {
            return response()->json([
                'success' => false,
                'message' => 'Kérjük, adja meg az építkezési címet a térképen!'
            ]);
        }

        // Calculate total volume
        $totalVolume = 0;
        foreach ($cart as $item) {
            if (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product && $product->volume_cbm) {
                    $totalVolume += $product->volume_cbm * $item['quantity'];
                }
            }
        }

        if ($totalVolume <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'A kosárban lévő termékek térfogata nem érhető el.'
            ]);
        }

        $deliveryPrice = $this->calculateDeliveryPrice($selectedLocationId, $constructionDistanceKm, $totalVolume);

        if ($deliveryPrice === null) {
            return response()->json([
                'success' => false,
                'message' => 'A fuvar díj nem kalkulálható a megadott távolságra.'
            ]);
        }

        // Store delivery data in session for later use
        session([
            'delivery_volume_cbm' => $totalVolume,
            'delivery_distance_km' => $constructionDistanceKm,
            'delivery_price_calculated' => $deliveryPrice
        ]);

        return response()->json([
            'success' => true,
            'distance' => number_format($constructionDistanceKm, 1, ',', ' '),
            'volume' => number_format($totalVolume, 2, ',', ' '),
            'price' => round($deliveryPrice, 0)
        ]);
    }

    /**
     * Store delivery choice in session
     */
    public function setDeliveryChoice(Request $request)
    {
        $needsDelivery = $request->input('needs_delivery', false);
        
        session(['needs_delivery' => $needsDelivery]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Check if construction address exists in session
     */
    public function checkConstructionAddress(Request $request)
    {
        $hasAddress = session()->has('construction_address') && !empty(session('construction_address'));
        
        return response()->json([
            'has_address' => $hasAddress,
            'address' => $hasAddress ? session('construction_address') : null
        ]);
    }

    /**
     * Get available pumps for the selected location, ordered by boom_length
     */
    public function getAvailablePumps(Request $request)
    {
        $selectedLocationId = session('selected_location_id');
        
        if (!$selectedLocationId) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs kiválasztott telephely!'
            ], 400);
        }

        $location = Location::find($selectedLocationId);
        
        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'A kiválasztott telephely nem található!'
            ], 404);
        }

        $pumps = $location->pumps()->orderBy('boom_length', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'pumps' => $pumps,
            'location_name' => $location->name
        ]);
    }

    /**
     * Store pump choice in session
     */
    public function setPumpChoice(Request $request)
    {
        $request->validate([
            'pump_id' => 'required|integer|exists:pumps,id',
            'estimated_hours' => 'required|numeric|min:0.5',
        ]);

        $pump = \App\Models\Pump::find($request->pump_id);
        
        if (!$pump) {
            return response()->json([
                'success' => false,
                'message' => 'A kiválasztott pumpa nem található!'
            ], 404);
        }

        session([
            'pump_id' => $pump->id,
            'pump_type' => $pump->type,
            'pump_boom_length' => $pump->boom_length,
            'pump_fixed_fee' => $pump->fixed_fee,
            'pump_hourly_fee' => $pump->hourly_fee,
            'pump_estimated_hours' => $request->estimated_hours,
        ]);
        
        return response()->json([
            'success' => true,
            'pump' => $pump
        ]);
    }
}
