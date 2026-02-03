<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fuvar Díj Szerkesztése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.delivery-prices.update', $deliveryPrice) }}">
                        @csrf
                        @method('PUT')

                        <!-- Location (read-only) -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                Telephely
                            </label>
                            <input type="text" value="{{ $deliveryPrice->location->name }}" readonly
                                   class="border-gray-300 bg-gray-100 rounded-md shadow-sm w-full">
                        </div>

                        <!-- Distance From -->
                        <div class="mb-4">
                            <label for="distance_from_km" class="block font-medium text-sm text-gray-700 mb-2">
                                Távolság-tól (km) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="distance_from_km" id="distance_from_km" 
                                   value="{{ old('distance_from_km', $deliveryPrice->distance_from_km) }}"
                                   step="1" min="0"
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                   required>
                            @error('distance_from_km')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Distance To -->
                        <div class="mb-4">
                            <label for="distance_to_km" class="block font-medium text-sm text-gray-700 mb-2">
                                Távolság-ig (km) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="distance_to_km" id="distance_to_km" 
                                   value="{{ old('distance_to_km', $deliveryPrice->distance_to_km) }}"
                                   step="1" min="0"
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                   required>
                            @error('distance_to_km')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price per CBM -->
                        <div class="mb-6">
                            <label for="price_per_cbm" class="block font-medium text-sm text-gray-700 mb-2">
                                Ár / m³ (Ft) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price_per_cbm" id="price_per_cbm" 
                                   value="{{ old('price_per_cbm', $deliveryPrice->price_per_cbm) }}"
                                   step="0.01" min="0"
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                   required>
                            @error('price_per_cbm')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.delivery-prices.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Vissza
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Mentés
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
