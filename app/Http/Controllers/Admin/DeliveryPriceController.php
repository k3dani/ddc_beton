<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPrice;
use App\Models\Location;
use Illuminate\Http\Request;

class DeliveryPriceController extends Controller
{
    public function index()
    {
        $locations = Location::with('deliveryPrices')->get();
        
        return view('admin.delivery-prices.index', compact('locations'));
    }

    public function create()
    {
        $locations = Location::all();
        
        return view('admin.delivery-prices.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'distance_from_km' => 'required|numeric|min:0',
            'distance_to_km' => 'required|numeric|min:0|gt:distance_from_km',
            'price_per_cbm' => 'required|numeric|min:0',
        ], [
            'distance_to_km.gt' => 'A távolság-ig értéknek nagyobbnak kell lennie, mint a távolság-tól.',
        ]);

        // Check for overlapping ranges
        $overlap = DeliveryPrice::where('location_id', $request->location_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('distance_from_km', [$request->distance_from_km, $request->distance_to_km])
                      ->orWhereBetween('distance_to_km', [$request->distance_from_km, $request->distance_to_km])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('distance_from_km', '<=', $request->distance_from_km)
                            ->where('distance_to_km', '>=', $request->distance_to_km);
                      });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['distance_from_km' => 'A megadott távolsági tartomány átfedésben van egy meglévő tartománnyal.']);
        }

        DeliveryPrice::create($request->all());

        return redirect()->route('admin.delivery-prices.index')
            ->with('success', 'Fuvar díj sikeresen létrehozva!');
    }

    public function edit(DeliveryPrice $deliveryPrice)
    {
        return view('admin.delivery-prices.edit', compact('deliveryPrice'));
    }

    public function update(Request $request, DeliveryPrice $deliveryPrice)
    {
        $request->validate([
            'distance_from_km' => 'required|numeric|min:0',
            'distance_to_km' => 'required|numeric|min:0|gt:distance_from_km',
            'price_per_cbm' => 'required|numeric|min:0',
        ], [
            'distance_to_km.gt' => 'A távolság-ig értéknek nagyobbnak kell lennie, mint a távolság-tól.',
        ]);

        // Check for overlapping ranges (excluding current record)
        $overlap = DeliveryPrice::where('location_id', $deliveryPrice->location_id)
            ->where('id', '!=', $deliveryPrice->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('distance_from_km', [$request->distance_from_km, $request->distance_to_km])
                      ->orWhereBetween('distance_to_km', [$request->distance_from_km, $request->distance_to_km])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('distance_from_km', '<=', $request->distance_from_km)
                            ->where('distance_to_km', '>=', $request->distance_to_km);
                      });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['distance_from_km' => 'A megadott távolsági tartomány átfedésben van egy meglévő tartománnyal.']);
        }

        $deliveryPrice->update($request->only(['distance_from_km', 'distance_to_km', 'price_per_cbm']));

        return redirect()->route('admin.delivery-prices.index')
            ->with('success', 'Fuvar díj sikeresen frissítve!');
    }

    public function destroy(DeliveryPrice $deliveryPrice)
    {
        $deliveryPrice->delete();

        return redirect()->route('admin.delivery-prices.index')
            ->with('success', 'Fuvar díj sikeresen törölve!');
    }
}
