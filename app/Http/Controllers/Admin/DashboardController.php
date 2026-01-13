<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'locations' => Location::count(),
            'products' => Product::count(),
            'active_locations' => Location::where('is_active', true)->count(),
            'active_products' => Product::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
