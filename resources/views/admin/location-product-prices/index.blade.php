<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Telephely - Termék - Ár mátrix') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.location-product-prices.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50 z-10">Termék</th>
                                        @foreach($locations as $location)
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase" colspan="2">
                                                {{ $location->name }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50 z-10"></th>
                                        @foreach($locations as $location)
                                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Bruttó (Ft)</th>
                                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Nettó (Ft)</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                @if($product->code)
                                                    <div class="text-xs text-gray-500">{{ $product->code }}</div>
                                                @endif
                                            </td>
                                            @foreach($locations as $location)
                                                @php
                                                    $price = $prices[$product->id][$location->id] ?? null;
                                                @endphp
                                                <td class="px-3 py-4">
                                                    <input type="hidden" name="prices[{{ $product->id }}_{{ $location->id }}][location_id]" value="{{ $location->id }}">
                                                    <input type="hidden" name="prices[{{ $product->id }}_{{ $location->id }}][product_id]" value="{{ $product->id }}">
                                                    <input 
                                                        type="number" 
                                                        name="prices[{{ $product->id }}_{{ $location->id }}][gross_price]" 
                                                        value="{{ $price['gross_price'] ?? '' }}"
                                                        step="0.01"
                                                        min="0"
                                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Bruttó"
                                                    >
                                                </td>
                                                <td class="px-3 py-4">
                                                    <input 
                                                        type="number" 
                                                        name="prices[{{ $product->id }}_{{ $location->id }}][net_price]" 
                                                        value="{{ $price['net_price'] ?? '' }}"
                                                        step="0.01"
                                                        min="0"
                                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Nettó"
                                                    >
                                                    <input 
                                                        type="hidden" 
                                                        name="prices[{{ $product->id }}_{{ $location->id }}][is_available]" 
                                                        value="{{ $price && $price['is_available'] ? '1' : '0' }}"
                                                    >
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Árak mentése
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 p-4 bg-gray-50 rounded">
                        <h3 class="font-semibold mb-2">Útmutató:</h3>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Írja be a bruttó és nettó árakat forintban minden telephelyre és termékre</li>
                            <li>Ha egy termék nem elérhető egy telephelyen, hagyja üresen az árakat</li>
                            <li>A mentés után az üres árak törlődnek az adatbázisból</li>
                            <li>A publikus oldalon csak azok a termékek jelennek meg, amelyekhez ár van megadva</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
