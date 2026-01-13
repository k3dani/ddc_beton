<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function homepage()
    {
        $googleMapsApiKey = config('services.google_maps.api_key', env('GOOGLE_MAPS_API_KEY'));
        
        // Aktív telephelyek betöltése az adatbázisból
        $locations = \App\Models\Location::where('is_active', true)
            ->select('id', 'name', 'slug', 'latitude', 'longitude')
            ->get();
        
        return view('pages.homepage', compact('googleMapsApiKey', 'locations'));
    }

    public function ecoblock()
    {
        return view('pages.ecoblock');
    }

    public function ecocrete()
    {
        return view('pages.ecocrete');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function quality()
    {
        return view('pages.quality');
    }

    public function shipping()
    {
        return view('pages.shipping');
    }

    public function tips()
    {
        return view('pages.tips');
    }

    public function news()
    {
        return view('pages.news');
    }
}
