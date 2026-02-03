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


                        <div class="mt-8 mb-8 pt-6 border-t flex gap-4">
                            <button type="button" id="openDeliveryPricesBtn" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">
                                Fuvardíjak
                            </button>
                                                        <button type="button" id="openPumpsBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                                                                Pumpák
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm7.5 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm7.5 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                                </svg>
                                                        </button>
                        </div>
    <!-- Pumpák Modal -->
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
                                Telephely mentése
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pumpák Modal -->
    <div id="pumpsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Pumpák - {{ $location->name }}</h3>
                <button type="button" id="closePumpsBtn" class="text-gray-400 text-2xl font-bold">&times;</button>
            </div>
            <div id="pumpsList">
                <!-- Ide jön a pumpák listája -->
                <p class="text-gray-600 mb-4" id="noPumpsMsg">Nincs még pumpa rögzítve ehhez a telephelyhez.</p>
                <div id="pumpsTableWrapper" class="hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pumpa típusa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gémhossz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fix díj</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Óradíj</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody id="pumpsTableBody" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-semibold mb-2">Új pumpa hozzáadása</h4>
                <form id="addPumpForm" autocomplete="off" onsubmit="return false;">
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-xs text-gray-600">Pumpa fajtája</label>
                            <input type="text" name="type" class="block w-full rounded border-gray-300 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Gémhossz (m)</label>
                            <input type="number" name="boom_length" min="1" step="0.1" class="block w-full rounded border-gray-300 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Fix díj (Ft)</label>
                            <input type="number" name="fixed_fee" min="0" class="block w-full rounded border-gray-300 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Óradíj (Ft/óra)</label>
                            <input type="number" name="hourly_fee" min="0" class="block w-full rounded border-gray-300 text-sm" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelPumpBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Mégse</button>
                        <button type="button" id="savePumpBtn" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded">Mentés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delivery Prices Modal -->
    <div id="deliveryPricesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-4xl shadow-lg rounded bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Fuvardíjak - {{ $location->name }}</h3>
                <button type="button" id="closeDeliveryPricesBtn" class="text-gray-400 text-2xl font-bold">&times;</button>
            </div>
            
            <div id="deliveryPricesContent">
                <p class="text-gray-600 mb-4">Adja meg a fuvardíjakat távolság szerint (Ft/m³):</p>
                
                <form id="deliveryPricesForm">
                    @csrf
                    <div class="grid grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2">
                        @for($i = 0; $i < 16; $i++)
                            @php
                                $from = $i * 5;
                                $to = ($i + 1) * 5;
                                $existing = $location->deliveryPrices->where('distance_from_km', $from)->where('distance_to_km', $to)->first();
                            @endphp
                            <div class="border rounded p-3 bg-gray-50">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $from }}-{{ $to }} km
                                </label>
                                <input type="hidden" name="prices[{{ $i }}][distance_from_km]" value="{{ $from }}">
                                <input type="hidden" name="prices[{{ $i }}][distance_to_km]" value="{{ $to }}">
                                <input type="hidden" name="prices[{{ $i }}][id]" value="{{ $existing->id ?? '' }}">
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Bruttó</label>
                                        <input type="number" 
                                               name="prices[{{ $i }}][price_per_cbm]" 
                                               value="{{ old('prices.' . $i . '.price_per_cbm', $existing->price_per_cbm ?? '') }}"
                                               class="delivery-gross-input w-full rounded border-gray-300 shadow-sm text-sm"
                                               data-index="{{ $i }}"
                                               step="1">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Nettó</label>
                                        <input type="number" 
                                               name="prices[{{ $i }}][price_per_cbm_net]" 
                                               value="{{ old('prices.' . $i . '.price_per_cbm_net', $existing->price_per_cbm_net ?? '') }}"
                                               class="delivery-net-input w-full rounded border-gray-300 shadow-sm text-sm"
                                               data-index="{{ $i }}"
                                               step="1">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" id="cancelDeliveryPricesBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                            Mégse
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                            Mentés
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Modal kezelés
        const modal = document.getElementById('deliveryPricesModal');
        const openBtn = document.getElementById('openDeliveryPricesBtn');
        const closeBtn = document.getElementById('closeDeliveryPricesBtn');
        const cancelBtn = document.getElementById('cancelDeliveryPricesBtn');
        const form = document.getElementById('deliveryPricesForm');

        openBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        function closeModal() {
            modal.classList.add('hidden');
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const data = {
                _token: '{{ csrf_token() }}',
                prices: []
            };

            // Collect all prices
            for (let i = 0; i < 16; i++) {
                const gross = formData.get(`prices[${i}][price_per_cbm]`);
                const net = formData.get(`prices[${i}][price_per_cbm_net]`);
                
                if (gross && net) {
                    data.prices.push({
                        id: formData.get(`prices[${i}][id]`) || null,
                        distance_from_km: formData.get(`prices[${i}][distance_from_km]`),
                        distance_to_km: formData.get(`prices[${i}][distance_to_km]`),
                        price_per_cbm: gross,
                        price_per_cbm_net: net
                    });
                }
            }

            // Save via AJAX
            fetch('{{ route("admin.locations.delivery-prices.update", $location) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Fuvardíjak sikeresen mentve!');
                    closeModal();
                } else {
                    alert('Hiba történt: ' + (data.message || 'Ismeretlen hiba'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hiba történt a mentés során!');
            });
        });

        // Automatikus nettó-bruttó számítás 27%-os ÁFA-val a fuvardíjaknál
        const VAT_RATE = 1.27;
        
        document.querySelectorAll('.delivery-gross-input').forEach(grossInput => {
            const index = grossInput.dataset.index;
            const netInput = document.querySelector(`.delivery-net-input[data-index="${index}"]`);
            
            if (netInput) {
                grossInput.addEventListener('input', function() {
                    if (this.value && this.value !== '') {
                        const gross = parseFloat(this.value);
                        const net = Math.round(gross / VAT_RATE);
                        netInput.value = net;
                    }
                });

                netInput.addEventListener('input', function() {
                    if (this.value && this.value !== '') {
                        const net = parseFloat(this.value);
                        const gross = Math.round(net * VAT_RATE);
                        grossInput.value = gross;
                    }
                });
            }
        });

        // Automatikus nettó-bruttó számítás 27%-os ÁFA-val a termékáraknál
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

    @push('scripts')
    <script>
    console.log('[Pump Modal] JS loaded v4.0');
    const pumpsModal = document.getElementById('pumpsModal');
    const openPumpsBtn = document.getElementById('openPumpsBtn');
    const closePumpsBtn = document.getElementById('closePumpsBtn');
    const cancelPumpBtn = document.getElementById('cancelPumpBtn');
    const addPumpForm = document.getElementById('addPumpForm');
    const pumpsList = document.getElementById('pumpsList');
    const noPumpsMsg = document.getElementById('noPumpsMsg');
    const savePumpBtn = document.getElementById('savePumpBtn');
    const locationId = {{ $location->id }};

    let editingPumpId = null;

    function renderPumps(pumps) {
        const tableBody = document.getElementById('pumpsTableBody');
        const tableWrapper = document.getElementById('pumpsTableWrapper');
        const noMsg = document.getElementById('noPumpsMsg');
        
        tableBody.innerHTML = '';
        
        if (!pumps.length) {
            noMsg.classList.remove('hidden');
            tableWrapper.classList.add('hidden');
            return;
        }
        
        noMsg.classList.add('hidden');
        tableWrapper.classList.remove('hidden');
        
        pumps.forEach(pump => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${pump.type}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${pump.boom_length} m</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${pump.fixed_fee.toLocaleString('hu-HU')} Ft</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${pump.hourly_fee.toLocaleString('hu-HU')} Ft/óra</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="editPump(${pump.id})" class="text-blue-600 hover:text-blue-900 mr-3">Szerkeszt</button>
                    <button onclick="deletePump(${pump.id})" class="text-red-600 hover:text-red-900">Töröl</button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function fetchPumps() {
        fetch(`/admin/locations/${locationId}/pumps`)
            .then(r => r.json())
            .then(renderPumps)
            .catch(() => {
                const tableBody = document.getElementById('pumpsTableBody');
                if (tableBody) {
                    tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Hiba a pumpák lekérdezésekor.</td></tr>';
                }
            });
    }

    if (openPumpsBtn && pumpsModal) {
        openPumpsBtn.addEventListener('click', function() {
            pumpsModal.classList.remove('hidden');
            fetchPumps();
        });
    }
    if (closePumpsBtn && pumpsModal) {
        closePumpsBtn.addEventListener('click', function() {
            pumpsModal.classList.add('hidden');
        });
    }
    if (cancelPumpBtn) {
        cancelPumpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addPumpForm.reset();
            editingPumpId = null;
            savePumpBtn.textContent = 'Mentés';
        });
    }

    if (savePumpBtn && addPumpForm) {
        console.log('[Pump Modal] Save button click listener attached');
        savePumpBtn.addEventListener('click', function(e) {
            console.log('[Pump Modal] Save button clicked!');
            e.preventDefault();
            savePumpBtn.disabled = true;
            const formData = new FormData(addPumpForm);
            console.log('[Pump Modal] Submitting form data:', Object.fromEntries(formData.entries()));
            
            const url = editingPumpId 
                ? `/admin/locations/${locationId}/pumps/${editingPumpId}`
                : `/admin/locations/${locationId}/pumps`;
            const method = editingPumpId ? 'PUT' : 'POST';
            
            const requestBody = editingPumpId 
                ? JSON.stringify(Object.fromEntries(formData.entries()))
                : formData;
            
            const headers = editingPumpId
                ? {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
                : {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                };
            
            fetch(url, {
                method: method,
                headers: headers,
                body: requestBody
            })
            .then(async r => {
                if (!r.ok) {
                    let msg = 'Hiba a mentéskor!';
                    try { const data = await r.json(); if (data.message) msg = data.message; console.error('[Pump Modal] Error response:', data); } catch (err) { console.error('[Pump Modal] Error parsing error response:', err); }
                    throw new Error(msg);
                }
                const json = await r.json();
                console.log('[Pump Modal] Success response:', json);
                return json;
            })
            .then(data => {
                if (data.success) {
                    addPumpForm.reset();
                    editingPumpId = null;
                    savePumpBtn.textContent = 'Mentés';
                    fetchPumps();
                } else {
                    alert(data.message || 'Hiba a mentéskor!');
                    console.error('[Pump Modal] Backend error:', data);
                }
            })
            .catch(err => {
                alert(err.message || 'Hiba a mentéskor!');
                console.error('[Pump Modal] AJAX error:', err);
            })
            .finally(() => {
                savePumpBtn.disabled = false;
            });
        });
    }

    window.editPump = function(pumpId) {
        fetch(`/admin/locations/${locationId}/pumps`)
            .then(r => r.json())
            .then(pumps => {
                const pump = pumps.find(p => p.id === pumpId);
                if (pump) {
                    editingPumpId = pumpId;
                    document.querySelector('#addPumpForm [name="type"]').value = pump.type;
                    document.querySelector('#addPumpForm [name="boom_length"]').value = pump.boom_length;
                    document.querySelector('#addPumpForm [name="fixed_fee"]').value = pump.fixed_fee;
                    document.querySelector('#addPumpForm [name="hourly_fee"]').value = pump.hourly_fee;
                    savePumpBtn.textContent = 'Módosítás';
                }
            });
    }

    window.deletePump = function(pumpId) {
        if (!confirm('Biztosan törölni szeretnéd ezt a pumpát?')) return;
        fetch(`/admin/locations/${locationId}/pumps/${pumpId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                fetchPumps();
            } else {
                alert('Hiba a törléskor!');
            }
        })
        .catch(() => {
            alert('Hiba a törléskor!');
        });
    }
    </script>
    @endpush
</x-app-layout>
