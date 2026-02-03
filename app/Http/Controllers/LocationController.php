<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Telephely kiválasztása és mentése session-be
     */
    public function select(Request $request, $slug)
    {
        $location = Location::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // FONTOS: Telephely váltáskor ürítjük a kosarat!
        // Mert a kosárban más telephely termékei lehetnek más árakkal
        session()->forget('cart');
        
        // Telephely mentése session-be
        session([
            'selected_location_id' => $location->id,
            'selected_location_name' => $location->name,
            'selected_location_slug' => $location->slug,
            'selected_location_latitude' => $location->latitude,
            'selected_location_longitude' => $location->longitude
        ]);
        
        session()->save();
        
        // Ha AJAX kérés, JSON választ küldünk
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'location' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'slug' => $location->slug,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude
                ]
            ]);
        }
        
        // Ha nem AJAX, átirányítjuk a shop-ba
        return redirect()->route('shop')->with('success', "Telephely kiválasztva: {$location->name}");
    }
    
    /**
     * Építési cím mentése és távolság ellenőrzése
     */
    public function saveAddress(Request $request)
    {
        $validated = $request->validate([
            'construction_address' => 'required|string|max:500',
            'construction_latitude' => 'nullable|numeric',
            'construction_longitude' => 'nullable|numeric',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
        ]);
        
        // Session-be mentés
        session([
            'construction_address' => $validated['construction_address'],
            'construction_postal_code' => $validated['postal_code'] ?? null,
            'construction_city' => $validated['city'] ?? null,
            'construction_street' => $validated['street'] ?? null,
            'construction_house_number' => $validated['house_number'] ?? null,
        ]);
        
        // Távolság számítása csak ha vannak koordináták
        $locationLat = session('selected_location_latitude');
        $locationLng = session('selected_location_longitude');
        
        if ($locationLat && $locationLng && $validated['construction_latitude'] && $validated['construction_longitude']) {
            $distance = $this->calculateDistance(
                $locationLat,
                $locationLng,
                $validated['construction_latitude'],
                $validated['construction_longitude']
            );
            
            session([
                'construction_latitude' => $validated['construction_latitude'],
                'construction_longitude' => $validated['construction_longitude'],
                'construction_distance_km' => round($distance, 2)
            ]);
            
            $warningMessage = null;
            if ($distance > 120) {
                $warningMessage = "Figyelem! Az építési cím több mint 120 km távolságra van a kiválasztott telephelytől (" . round($distance, 1) . " km). Ez különdíjat vonhat magával.";
            }
            
            return response()->json([
                'success' => true,
                'distance' => round($distance, 2),
                'warning' => $warningMessage
            ]);
        }
        
        // Ha nincs koordináta, csak a címet mentjük
        return response()->json([
            'success' => true,
            'distance' => null,
            'warning' => null
        ]);
    }
    
    /**
     * Távolság számítása Haversine képlettel (km-ben)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
    /**
     * Kiválasztott telephely törlése
     */
    public function clear(Request $request)
    {
        session()->forget(['selected_location_id', 'selected_location_name', 'selected_location_slug', 'selected_location_latitude', 'selected_location_longitude', 'construction_address', 'construction_latitude', 'construction_longitude', 'construction_distance_km']);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('homepage');
    }
}
