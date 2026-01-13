<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->ordered()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::ordered()->get();
        $locations = Location::active()->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_category_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // Attach locations with prices
        if ($request->has('location_prices')) {
            $syncData = [];
            foreach ($request->location_prices as $locationId => $priceData) {
                if (!empty($priceData['gross_price']) && !empty($priceData['net_price'])) {
                    $syncData[$locationId] = [
                        'gross_price' => $priceData['gross_price'],
                        'net_price' => $priceData['net_price'],
                        'is_available' => isset($priceData['is_available']),
                    ];
                }
            }
            $product->locations()->sync($syncData);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Termék sikeresen létrehozva!');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'locations']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::ordered()->get();
        $locations = Location::active()->orderBy('name')->get();
        $productLocations = $product->locations->keyBy('id');
        
        return view('admin.products.edit', compact('product', 'categories', 'locations', 'productLocations'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_category_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        // Update locations with prices
        if ($request->has('location_prices')) {
            $syncData = [];
            foreach ($request->location_prices as $locationId => $priceData) {
                if (!empty($priceData['gross_price']) && !empty($priceData['net_price'])) {
                    $syncData[$locationId] = [
                        'gross_price' => $priceData['gross_price'],
                        'net_price' => $priceData['net_price'],
                        'is_available' => isset($priceData['is_available']),
                    ];
                }
            }
            $product->locations()->sync($syncData);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Termék sikeresen frissítve!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Termék sikeresen törölve!');
    }
}
