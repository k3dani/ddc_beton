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
        
        // Telephely mentése session-be
        session([
            'selected_location_id' => $location->id,
            'selected_location_name' => $location->name,
            'selected_location_slug' => $location->slug
        ]);
        
        // Ha AJAX kérés, JSON választ küldünk
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'location' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'slug' => $location->slug
                ]
            ]);
        }
        
        // Ha nem AJAX, átirányítjuk a shop-ba
        return redirect()->route('shop')->with('success', "Telephely kiválasztva: {$location->name}");
    }
    
    /**
     * Kiválasztott telephely törlése
     */
    public function clear(Request $request)
    {
        session()->forget(['selected_location_id', 'selected_location_name', 'selected_location_slug']);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('homepage');
    }
}
