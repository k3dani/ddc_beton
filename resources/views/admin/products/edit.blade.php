<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Termék szerkesztése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kategóriák</label>
                            <div class="border rounded p-4 bg-gray-50">
                                @foreach($categories as $category)
                                    <div class="mb-2 flex items-center">
                                        <input type="checkbox" 
                                               name="category_ids[]" 
                                               value="{{ $category->id }}" 
                                               id="category_{{ $category->id }}"
                                               {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) || (!old('category_ids') && $product->categories->contains($category->id)) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="category_{{ $category->id }}" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-gray-600 text-xs mt-1">Válaszd ki azokat a kategóriákat, amelyekbe a termék tartozik</p>
                            @error('category_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Név</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Technikai név</label>
                            <input type="text" name="technical_name" value="{{ old('technical_name', $product->technical_name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="pl. C25/30 F2">
                            <p class="text-gray-600 text-xs mt-1">Ez a név a termék neve alatt jelenik meg kisebb betűvel</p>
                            @error('technical_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Egység</label>
                            <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('unit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Rövid leírás</label>
                            <textarea name="short_description" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Leírás</label>
                            <textarea name="description" rows="5" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jelenlegi kép</label>
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="max-h-32 max-w-40 object-contain border border-gray-300 rounded mb-2">
                            @endif
                            <input type="file" name="image" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Aktív</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Frissítés
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">Mégse</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
