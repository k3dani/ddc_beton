<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Telephely szerkesztése: {{ $location->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold mb-4">Alapadatok</h3>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Név *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Város</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $location->city) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $location->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Cím</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $location->address) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <!-- Hidden fields for coordinates - automatically filled by backend -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}">

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $location->email) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="flex items-center mt-6">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm">
                                    <span class="ml-2 text-sm text-gray-700">Aktív</span>
                                </label>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold mb-4 mt-8">Termékárak ezen a telephelyen</h3>
                        <p class="text-sm text-gray-600 mb-4">Adja meg az egyes termékek árait ezen a telephelyen.</p>

                        <div class="space-y-3">
                            @foreach($products as $product)
                                @php
                                    $existingPrice = $location->products->find($product->id);
                                @endphp
                                <div class="border rounded p-4 bg-gray-50">
                                    <div class="grid grid-cols-5 gap-4 items-center">
                                        <div class="col-span-2">
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->code)
                                                <span class="text-sm text-gray-600">({{ $product->code }})</span>
                                            @endif
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600">Bruttó (Ft)</label>
                                            <input type="number" step="0.01" name="product_prices[{{ $product->id }}][gross_price]"
                                                   value="{{ $existingPrice ? $existingPrice->pivot->gross_price : '' }}"
                                                   placeholder="Bruttó ár"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600">Nettó (Ft)</label>
                                            <input type="number" step="0.01" name="product_prices[{{ $product->id }}][net_price]"
                                                   value="{{ $existingPrice ? $existingPrice->pivot->net_price : '' }}"
                                                   placeholder="Nettó ár"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="product_prices[{{ $product->id }}][is_available]" value="1"
                                                       {{ $existingPrice && $existingPrice->pivot->is_available ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                                                <span class="ml-2 text-sm">Elérhető</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('admin.locations.index') }}" class="text-gray-600 hover:text-gray-900">
                                Vissza
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Mentés
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Automatikus nettó-bruttó számítás 27%-os ÁFA-val
        document.addEventListener('DOMContentLoaded', function() {
            const VAT_RATE = 1.27;
            console.log('🧮 Price calculator loaded');

            document.querySelectorAll('input[name*="[gross_price]"]').forEach(grossInput => {
                const productId = grossInput.name.match(/\[(\d+)\]/)[1];
                const netInput = document.querySelector(`input[name="product_prices[${productId}][net_price]"]`);
                
                console.log(`Setting up calculator for product ${productId}`);
                
                if (netInput) {
                    // Ha bruttó változik, számoljuk ki a nettót
                    grossInput.addEventListener('input', function() {
                        if (this.value && this.value !== '') {
                            const gross = parseFloat(this.value);
                            const net = Math.round(gross / VAT_RATE);
                            netInput.value = net;
                            console.log(`Bruttó: ${gross} → Nettó: ${net}`);
                        }
                    });

                    // Ha nettó változik, számoljuk ki a bruttót
                    netInput.addEventListener('input', function() {
                        if (this.value && this.value !== '') {
                            const net = parseFloat(this.value);
                            const gross = Math.round(net * VAT_RATE);
                            grossInput.value = gross;
                            console.log(`Nettó: ${net} → Bruttó: ${gross}`);
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
