<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Location;
use App\Models\Pump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PumpController extends Controller
{
    use AuthorizesRequests;
    public function index(Location $location)
    {
        $pumps = $location->pumps()->orderBy('type')->get();
        return response()->json($pumps);
    }

    public function store(Request $request, Location $location)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'boom_length' => 'required|numeric|min:1',
            'fixed_fee' => 'required|integer|min:0',
            'hourly_fee' => 'required|integer|min:0',
        ]);
        $validated['location_id'] = $location->id;
        $pump = Pump::create($validated);
        return response()->json(['success' => true, 'pump' => $pump]);
    }

    public function update(Request $request, Location $location, Pump $pump)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'boom_length' => 'required|numeric|min:1',
            'fixed_fee' => 'required|integer|min:0',
            'hourly_fee' => 'required|integer|min:0',
        ]);
        $pump->update($validated);
        return response()->json(['success' => true, 'pump' => $pump]);
    }

    public function destroy(Location $location, Pump $pump)
    {
        $pump->delete();
        return response()->json(['success' => true]);
    }
}
