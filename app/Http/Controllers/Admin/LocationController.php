<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Location::create($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Telephely sikeresen létrehozva!');
    }

    public function show(Location $location)
    {
        $location->load('products');
        return view('admin.locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        $products = Product::active()->ordered()->get();
        $locationProducts = $location->products->pluck('id')->toArray();
        
        return view('admin.locations.edit', compact('location', 'products', 'locationProducts'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $location->update($validated);

        // Update product prices
        if ($request->has('product_prices')) {
            $syncData = [];
            foreach ($request->product_prices as $productId => $priceData) {
                if (!empty($priceData['gross_price']) && !empty($priceData['net_price'])) {
                    $syncData[$productId] = [
                        'gross_price' => $priceData['gross_price'],
                        'net_price' => $priceData['net_price'],
                        'is_available' => isset($priceData['is_available']),
                    ];
                }
            }
            $location->products()->sync($syncData);
        }

        return redirect()->route('admin.locations.index')
            ->with('success', 'Telephely sikeresen frissítve!');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Telephely sikeresen törölve!');
    }
}
