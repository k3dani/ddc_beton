@extends('layouts.public')

@section('title', 'Kosár')

@section('content')
<div class="page page-cart">
    <div class="container-fluid" style="margin-top: 50px; margin-bottom: 100px;">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h1 style="color: #004E2B; margin: 0;">Kosár</h1>
                    @if(!empty($cart))
                        <form method="POST" action="{{ route('cart.clear') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" onclick="return confirm('Biztosan törölni szeretnéd a teljes kosár tartalmát?')" 
                                    style="padding: 10px 20px; background: #dc3545; color: #fff; border: none; cursor: pointer; font-size: 14px;">
                                Kosár ürítése
                            </button>
                        </form>
                    @endif
                </div>

                @if(empty($cart))
                    <div style="background: #fff; padding: 60px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <p style="font-size: 18px; color: #666; margin-bottom: 30px;">A kosár üres</p>
                        <a href="{{ route('shop') }}" style="display: inline-block; padding: 15px 40px; background: #004E2B; color: #fff; text-decoration: none; font-weight: 500;">
                            Vissza a boltba
                        </a>
                    </div>
                @else
                    <div style="background: #fff; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <p style="margin-bottom: 20px; color: #666;">
                            <strong>{{ count($cart) }}</strong> tétel a kosárban
                        </p>
                        
                        @foreach($cart as $key => $item)
                            <div style="border-bottom: 1px solid #eee; padding: 20px 0; display: flex; justify-content: space-between; align-items: center;">
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 10px 0; color: #004E2B; font-size: 18px;">{{ $item['product_name'] }}</h3>
                                    <p style="margin: 0; color: #666;">{{ $item['quantity'] }} {{ $item['unit'] ?? 'm³' }} × {{ number_format($item['gross_price'], 0, ',', ' ') }} Ft</p>
                                </div>
                                <div style="text-align: right;">
                                    <p style="margin: 0; font-size: 20px; font-weight: 700; color: #004E2B;">
                                        {{ number_format($item['gross_price'] * $item['quantity'], 0, ',', ' ') }} Ft
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #004E2B; text-align: right;">
                            <p style="font-size: 24px; font-weight: 700; color: #004E2B; margin: 0 0 30px 0;">
                                Végösszeg: {{ number_format($total, 0, ',', ' ') }} Ft
                            </p>
                            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                <a href="{{ route('shop') }}" style="padding: 15px 30px; background: #fff; color: #004E2B; border: 2px solid #004E2B; text-decoration: none; font-weight: 500;">
                                    Folytatom a vásárlást
                                </a>
                                <button id="checkoutButton" type="button" style="padding: 15px 30px; background: #004E2B; color: #fff; border: none; cursor: pointer; font-weight: 500;">
                                    Megrendelés véglegesítése
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
    </div>
</div>

<!-- Delivery & Pump Service Modal -->
<div id="deliveryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center; overflow-y: auto;">
    <div style="background: #fff; padding: 40px; max-width: 700px; width: 90%; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.3); margin: 20px;">
        <button onclick="closeDeliveryModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer; color: #666;">&times;</button>
        
        <h2 style="color: #004E2B; margin-bottom: 15px; font-size: 24px;">Kiegészítő szolgáltatások</h2>
        <p style="color: #666; margin-bottom: 30px; font-size: 15px;">Válassza ki, mely szolgáltatásokat igényli a beton szállítása mellett:</p>
        
        <div id="deliveryContent">
            <!-- FUVAR SZOLGÁLTATÁS -->
            <div style="border: 2px solid #004E2B; padding: 25px; margin-bottom: 20px; border-radius: 8px; background: #f8fdf9;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <input type="checkbox" id="needsDeliveryCheckbox" style="width: 20px; height: 20px; margin-right: 10px; cursor: pointer;">
                    <label for="needsDeliveryCheckbox" style="font-size: 18px; font-weight: 600; color: #004E2B; cursor: pointer; margin: 0;">
                        🚚 Kérek fuvar szolgáltatást az építkezési címre
                    </label>
                </div>
                
                <div id="deliveryInfo" style="display: none; background: #f8f9fa; padding: 15px; margin-top: 15px; border-left: 4px solid #004E2B;">
                    <p style="margin: 5px 0; color: #666;"><strong>Távolság:</strong> <span id="distanceDisplay"></span> km</p>
                    <p style="margin: 5px 0; color: #666;"><strong>Mennyiség:</strong> <span id="volumeDisplay"></span> m³</p>
                    <p style="margin: 15px 0 5px 0; font-size: 18px; font-weight: 700; color: #004E2B;"><strong>Szállítási díj:</strong> <span id="deliveryPriceDisplay"></span> Ft</p>
                </div>
                
                <div id="deliveryError" style="display: none; background: #fff3cd; padding: 15px; margin-top: 15px; border-left: 4px solid #ffc107; color: #856404;">
                    <p style="margin: 0;"></p>
                </div>
            </div>

            <!-- PUMPA SZOLGÁLTATÁS -->
            <div style="border: 2px solid #ffc107; padding: 25px; margin-bottom: 25px; border-radius: 8px; background: #fffef8;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <input type="checkbox" id="needsPumpCheckbox" style="width: 20px; height: 20px; margin-right: 10px; cursor: pointer;">
                    <label for="needsPumpCheckbox" style="font-size: 18px; font-weight: 600; color: #856404; cursor: pointer; margin: 0;">
                        🏗️ Kérek pumpa szolgáltatást (betonpumpa bérbeadás)
                    </label>
                </div>
                <p style="margin: 0 0 15px 30px; color: #666; font-size: 14px;">A betonpumpa segítségével nehezen megközelíthető helyekre is könnyedén eljuttathatja a betont.</p>
                
                <div id="pumpSelection" style="display: none; margin-top: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #666;">Válasszon pumpát:</label>
                    <select id="pumpSelect" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 4px; font-size: 16px;">
                        <option value="">-- Válasszon pumpát --</option>
                    </select>
                    
                    <div id="pumpDetails" style="display: none; background: #f8f9fa; padding: 15px; margin-top: 15px; border-left: 4px solid #004E2B;">
                        <p style="margin: 5px 0; color: #666;"><strong>Gémhossz:</strong> <span id="pumpBoomDisplay"></span> m</p>
                        <p style="margin: 5px 0; color: #666;"><strong>Fix díj:</strong> <span id="pumpFixedFeeDisplay"></span> Ft</p>
                        <p style="margin: 5px 0; color: #666; font-size: 13px;"><em>Óradíj: <span id="pumpHourlyFeeDisplay"></span> Ft/óra (tényleges munkaórák alapján számlázva)</em></p>
                    </div>
                    
                    <div id="pumpError" style="display: none; background: #fff3cd; padding: 15px; margin-top: 15px; border-left: 4px solid #ffc107; color: #856404;">
                        <p style="margin: 0;"></p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                <button onclick="closeDeliveryModal()" style="padding: 12px 30px; background: #fff; color: #666; border: 2px solid #ccc; cursor: pointer; font-weight: 500;">
                    Mégse
                </button>
                <button onclick="proceedToCheckout()" id="proceedBtn" style="padding: 12px 30px; background: #004E2B; color: #fff; border: none; cursor: pointer; font-weight: 500;">
                    Tovább a rendeléshez
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('🛒 CART SCRIPT INLINE LOADED!');
console.log('🛒 Cart scripts loaded!');

let availablePumps = [];
let selectedPumpId = null;

// Add event listener when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎬 DOMContentLoaded event fired!');
    const checkoutBtn = document.getElementById('checkoutButton');
    console.log('🔍 Checkout button found:', checkoutBtn);
    
    if (checkoutBtn) {
        console.log('✅ Adding click listener to checkout button');
        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🎯 Checkout button clicked!');
            alert('Checkout button clicked! Modal should open now.');
            openDeliveryModal();
        });
    } else {
        console.error('❌ Checkout button NOT FOUND!');
    }

    // Delivery checkbox handler
    const deliveryCheckbox = document.getElementById('needsDeliveryCheckbox');
    if (deliveryCheckbox) {
        deliveryCheckbox.addEventListener('change', function() {
            if (this.checked) {
                calculateDeliveryPrice();
            } else {
                document.getElementById('deliveryInfo').style.display = 'none';
                document.getElementById('deliveryError').style.display = 'none';
            }
        });
    }

    // Pump checkbox handler
    const pumpCheckbox = document.getElementById('needsPumpCheckbox');
    if (pumpCheckbox) {
        pumpCheckbox.addEventListener('change', function() {
            const pumpSelection = document.getElementById('pumpSelection');
            if (this.checked) {
                pumpSelection.style.display = 'block';
                loadAvailablePumps();
            } else {
                pumpSelection.style.display = 'none';
                document.getElementById('pumpDetails').style.display = 'none';
                document.getElementById('pumpError').style.display = 'none';
                selectedPumpId = null;
            }
        });
    }

    // Pump select handler
    const pumpSelect = document.getElementById('pumpSelect');
    if (pumpSelect) {
        pumpSelect.addEventListener('change', function() {
            const pumpId = parseInt(this.value);
            if (pumpId) {
                const pump = availablePumps.find(p => p.id === pumpId);
                if (pump) {
                    selectedPumpId = pumpId;
                    document.getElementById('pumpBoomDisplay').textContent = pump.boom_length;
                    document.getElementById('pumpFixedFeeDisplay').textContent = new Intl.NumberFormat('hu-HU').format(pump.fixed_fee);
                    document.getElementById('pumpHourlyFeeDisplay').textContent = new Intl.NumberFormat('hu-HU').format(pump.hourly_fee);
                    document.getElementById('pumpDetails').style.display = 'block';
                    document.getElementById('pumpError').style.display = 'none';
                }
            } else {
                selectedPumpId = null;
                document.getElementById('pumpDetails').style.display = 'none';
            }
        });
    }
});

function openDeliveryModal() {
    console.log('🚚 openDeliveryModal called!');
    const modal = document.getElementById('deliveryModal');
    console.log('📦 Modal element:', modal);
    console.log('📦 Modal current display:', modal ? modal.style.display : 'null');
    modal.style.display = 'flex';
    console.log('✅ Modal should now be visible with display: flex');
}

function closeDeliveryModal() {
    document.getElementById('deliveryModal').style.display = 'none';
    // Reset form
    document.getElementById('needsDeliveryCheckbox').checked = false;
    document.getElementById('needsPumpCheckbox').checked = false;
    document.getElementById('deliveryInfo').style.display = 'none';
    document.getElementById('pumpSelection').style.display = 'none';
    document.getElementById('pumpDetails').style.display = 'none';
    selectedPumpId = null;
}

function calculateDeliveryPrice() {
    fetch('/delivery/calculate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delivery calculation response:', data);
        if (data.success) {
            document.getElementById('distanceDisplay').textContent = data.distance;
            document.getElementById('volumeDisplay').textContent = data.volume;
            document.getElementById('deliveryPriceDisplay').textContent = new Intl.NumberFormat('hu-HU').format(data.price);
            document.getElementById('deliveryInfo').style.display = 'block';
            document.getElementById('deliveryError').style.display = 'none';
        } else {
            document.getElementById('deliveryError').querySelector('p').textContent = data.message;
            document.getElementById('deliveryError').style.display = 'block';
            document.getElementById('deliveryInfo').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('deliveryError').querySelector('p').textContent = 'Hiba történt a fuvar díj számítása során.';
        document.getElementById('deliveryError').style.display = 'block';
        document.getElementById('deliveryInfo').style.display = 'none';
    });
}

function loadAvailablePumps() {
    fetch('/pump/get-available', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Available pumps:', data);
        if (data.success && data.pumps) {
            availablePumps = data.pumps;
            const pumpSelect = document.getElementById('pumpSelect');
            pumpSelect.innerHTML = '<option value="">-- Válasszon pumpát --</option>';
            
            data.pumps.forEach(pump => {
                const option = document.createElement('option');
                option.value = pump.id;
                option.textContent = `${pump.type} (${pump.boom_length} m gémhossz)`;
                pumpSelect.appendChild(option);
            });
            
            document.getElementById('pumpError').style.display = 'none';
        } else {
            document.getElementById('pumpError').querySelector('p').textContent = data.message || 'Nem található elérhető pumpa.';
            document.getElementById('pumpError').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error loading pumps:', error);
        document.getElementById('pumpError').querySelector('p').textContent = 'Hiba történt a pumpák betöltése során.';
        document.getElementById('pumpError').style.display = 'block';
    });
}

function proceedToCheckout() {
    const needsDelivery = document.getElementById('needsDeliveryCheckbox').checked;
    const needsPump = document.getElementById('needsPumpCheckbox').checked;
    
    // Validate pump selection if pump checkbox is checked
    if (needsPump && !selectedPumpId) {
        alert('Kérjük, válasszon pumpát!');
        return;
    }
    
    // Save delivery choice
    const deliveryPromise = fetch('/delivery/set-choice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ needs_delivery: needsDelivery })
    }).then(r => r.json());
    
    // Save pump choice if selected
    const pumpPromise = needsPump && selectedPumpId ? 
        fetch('/pump/set-choice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pump_id: selectedPumpId })
        }).then(r => r.json()) : 
        Promise.resolve({ success: true });
    
    // Wait for both requests to complete
    Promise.all([deliveryPromise, pumpPromise])
        .then(([deliveryData, pumpData]) => {
            console.log('Delivery choice:', deliveryData);
            console.log('Pump choice:', pumpData);
            
            if (deliveryData.success && pumpData.success) {
                window.location.href = '{{ route("checkout.index") }}';
            } else {
                alert('Hiba történt a választás mentése során.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a választás mentése során.');
        });
}
</script>
@endpush
