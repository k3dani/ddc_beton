<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Product;

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
        foreach ($cart as $item) {
            $total += $item['gross_price'] * $item['quantity'];
        }

        return view('pages.checkout', compact('cart', 'selectedLocation', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'A kosár üres!');
        }

        // TODO: Itt menthetnéd az adatbázisba a rendelést
        // Most csak logoljuk
        \Log::info('Új rendelés', [
            'customer' => $request->only(['name', 'email', 'phone', 'address', 'delivery_address', 'notes']),
            'cart' => $cart,
            'location_id' => session('selected_location_id')
        ]);

        // Kosár ürítése
        session()->forget('cart');
        session()->save(); // Biztosan mentsük a sessiont

        return redirect()->route('checkout.success');
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
}
