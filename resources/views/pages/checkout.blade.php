@extends('layouts.public')

@section('title', 'Rendelés véglegesítése')

@section('content')
<div class="page page-checkout">
    <div class="container-fluid" style="margin-top: 50px; margin-bottom: 100px;">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h1 style="color: #004E2B; margin-bottom: 40px; text-align: center;">Rendelés véglegesítése</h1>

                @if($selectedLocation)
                    <div style="background: #f8f9fa; border: 2px solid #004E2B; padding: 20px; margin-bottom: 30px;">
                        <strong style="color: #004E2B; font-size: 18px;">Kiválasztott telephely:</strong> 
                        <span style="font-size: 18px;">{{ $selectedLocation->name }}</span>
                    </div>
                @endif

                <!-- Kosár összesítő -->
                <div style="background: #fff; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h2 style="color: #004E2B; margin-bottom: 25px; font-size: 22px;">Megrendelt termékek</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #ddd;">
                                <th style="text-align: left; padding: 12px; font-weight: 600;">Termék</th>
                                <th style="text-align: center; padding: 12px; font-weight: 600;">Mennyiség</th>
                                <th style="text-align: right; padding: 12px; font-weight: 600;">Egységár</th>
                                <th style="text-align: right; padding: 12px; font-weight: 600;">Összeg</th>
                                <th style="text-align: center; padding: 12px; font-weight: 600;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $key => $item)
                                <tr style="border-bottom: 1px solid #eee;" data-cart-key="{{ $key }}">
                                    <td style="padding: 15px;">{{ $item['product_name'] }}</td>
                                    <td style="text-align: center; padding: 15px;">
                                        <input type="number" 
                                               class="quantity-input" 
                                               data-cart-key="{{ $key }}"
                                               data-unit-price="{{ $item['gross_price'] }}"
                                               data-volume-cbm="{{ $item['volume_cbm'] ?? 1 }}"
                                               value="{{ $item['quantity'] }}" 
                                               min="0.5" 
                                               step="0.5"
                                               style="width: 80px; padding: 8px; border: 1px solid #ddd; text-align: center;">
                                        <span>{{ $item['unit'] ?? 'm³' }}</span>
                                    </td>
                                    <td style="text-align: right; padding: 15px;">{{ number_format($item['gross_price'], 0, ',', ' ') }} Ft</td>
                                    <td style="text-align: right; padding: 15px; font-weight: 600;" class="item-total">
                                        {{ number_format($item['gross_price'] * $item['quantity'], 0, ',', ' ') }} Ft
                                    </td>
                                    <td style="text-align: center; padding: 15px;">
                                        <button type="button" 
                                                class="remove-item" 
                                                data-cart-key="{{ $key }}"
                                                style="background: #dc3545; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 3px; font-size: 14px;">
                                            Törlés
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #ddd;">
                                <td colspan="3" style="text-align: right; padding: 15px; font-size: 16px; font-weight: 600;">
                                    Részösszeg:
                                </td>
                                <td style="text-align: right; padding: 15px; font-size: 18px; font-weight: 600;" id="subtotal">
                                    {{ number_format($total, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                            <tr id="delivery-row" style="display: none; border-top: 1px solid #eee;">
                                <td colspan="3" style="text-align: right; padding: 15px; font-size: 16px; font-weight: 600; color: #004E2B;">
                                    Várható fuvardíj:
                                </td>
                                <td style="text-align: right; padding: 15px; font-size: 18px; font-weight: 600; color: #004E2B;" id="delivery-total">
                                    {{ number_format($deliveryPrice ?? 0, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                            @if($pumpId)
                            <tr id="pump-fixed-row" style="border-top: 1px solid #eee;">
                                <td colspan="3" style="text-align: right; padding: 15px; font-size: 16px; font-weight: 600; color: #856404;">
                                    Pumpa fix díj:
                                </td>
                                <td style="text-align: right; padding: 15px; font-size: 18px; font-weight: 600; color: #856404;" id="pump-fixed-total">
                                    {{ number_format($pumpFixedFee, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                            <tr id="pump-hourly-row" style="border-top: 1px solid #eee;">
                                <td colspan="3" style="text-align: right; padding: 15px; font-size: 16px; font-weight: 600; color: #856404;">
                                    Pumpa óradíj ({{ number_format($pumpEstimatedHours, 1, ',', ' ') }} óra):
                                </td>
                                <td style="text-align: right; padding: 15px; font-size: 18px; font-weight: 600; color: #856404;" id="pump-hourly-total">
                                    {{ number_format($pumpHourlyFee * $pumpEstimatedHours, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                            @endif
                            <tr style="border-top: 2px solid #004E2B;">
                                <td colspan="3" style="text-align: right; padding: 20px 15px; font-size: 20px; font-weight: 700; color: #004E2B;">
                                   Irányár:
                                </td>
                                <td style="text-align: right; padding: 20px 15px; font-size: 24px; font-weight: 700; color: #004E2B;" id="grand-total">
                                    {{ number_format($total, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Fuvar szolgáltatás -->
                @if(session('needs_delivery'))
                <div style="background: #e8f5e9; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; border-left: 4px solid #004E2B;">
                    <h2 style="color: #004E2B; margin-bottom: 25px; font-size: 22px;">✓ Fuvar szolgáltatás igényelve</h2>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Építési cím távolsága:</strong> 
                        <span style="color: #004E2B; font-weight: 600;">{{ number_format($constructionDistanceKm ?? 0, 2, ',', ' ') }} km</span>
                    </div>
                    
                    @if($constructionAddress)
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #c8e6c9; font-size: 14px; color: #666;">
                        <strong>Építési cím:</strong> {{ $constructionAddress }}
                    </div>
                    @endif
                </div>
                @elseif(session()->has('needs_delivery') && !session('needs_delivery'))
                <div style="background: #f8f9fa; padding: 20px; margin-bottom: 30px; border-left: 4px solid #6c757d; color: #333;">
                    <p style="margin: 0; font-size: 16px;">
                        <strong>Fuvar szolgáltatás:</strong> Nem kért fuvar szolgáltatást.
                    </p>
                </div>
                @endif

                <!-- Pumpa szolgáltatás -->
                @if($pumpId)
                <div style="background: #fff3cd; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; border-left: 4px solid #ffc107;">
                    <h2 style="color: #856404; margin-bottom: 25px; font-size: 22px;">✓ Pumpa szolgáltatás igényelve</h2>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Pumpa típus:</strong> 
                        <span style="color: #856404; font-weight: 600;">{{ $pumpType }}</span>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Gémhossz:</strong> 
                        <span style="color: #856404; font-weight: 600;">{{ number_format($pumpBoomLength, 0, ',', ' ') }} m</span>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Fix díj:</strong> 
                        <span style="color: #856404; font-weight: 600;">{{ number_format($pumpFixedFee, 0, ',', ' ') }} Ft</span>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Becsült használati idő:</strong> 
                        <span style="color: #856404; font-weight: 600;">{{ number_format($pumpEstimatedHours, 1, ',', ' ') }} óra</span>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <strong>Becsült óradíj:</strong> 
                        <span style="color: #856404; font-weight: 600;">{{ number_format($pumpHourlyFee * $pumpEstimatedHours, 0, ',', ' ') }} Ft</span>
                        <span style="font-size: 13px; color: #666;">({{ number_format($pumpEstimatedHours, 1, ',', ' ') }} óra × {{ number_format($pumpHourlyFee, 0, ',', ' ') }} Ft/óra)</span>
                    </div>
                    
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ffeaa7; font-size: 14px; color: #856404;">
                        <strong>Megjegyzés:</strong> A tényleges óradíj a valós munkavégzési idő alapján kerül számlázásra.
                    </div>
                </div>
                @endif

                <!-- Vásárlói adatok form -->
                <div style="background: #fff; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="color: #004E2B; margin-bottom: 25px; font-size: 22px;">Adatok megadása</h2>
                    
                    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                        @csrf

                        <!-- Magánszemély / Cég kapcsoló -->
                        <div style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 4px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span style="font-weight: 600; color: #333; font-size: 16px;">Magánszemély</span>
                                <label style="position: relative; display: inline-block; width: 60px; height: 30px; cursor: pointer;">
                                    <input type="checkbox" id="customer-type-toggle" name="is_company" value="1" {{ old('is_company') ? 'checked' : '' }}
                                           style="opacity: 0; width: 0; height: 0;">
                                    <span id="toggle-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 30px;">
                                        <span id="toggle-knob" style="position: absolute; content: ''; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                                    </span>
                                </label>
                                <span style="font-weight: 600; color: #333; font-size: 16px;">Cég</span>
                            </div>
                        </div>

                        <!-- Cégnév és adószám (csak cég esetén) -->
                        <div id="company-fields" style="display: {{ old('is_company') ? 'block' : 'none' }};">
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Cégnév *</label>
                                <input type="text" name="company_name" id="company-name" value="{{ old('company_name') }}"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('company_name')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Adószám *</label>
                                <input type="text" name="tax_number" id="tax-number" value="{{ old('tax_number') }}"
                                       placeholder="12345678-1-23"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('tax_number')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Név *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                            @error('name')
                                <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">E-mail cím *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                            @error('email')
                                <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Telefonszám *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                            @error('phone')
                                <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <h3 style="color: #004E2B; margin: 30px 0 20px 0; font-size: 18px;">Számlázási cím</h3>

                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Irányítószám *</label>
                                <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" required
                                       maxlength="4" pattern="[0-9]{4}" placeholder="pl. 2600"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('billing_postal_code')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Város *</label>
                                <input type="text" name="billing_city" value="{{ old('billing_city') }}" required
                                       placeholder="pl. Vác"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('billing_city')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Utca *</label>
                                <input type="text" name="billing_street" value="{{ old('billing_street') }}" required
                                       placeholder="pl. Kossuth utca"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('billing_street')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Házszám *</label>
                                <input type="text" name="billing_house_number" value="{{ old('billing_house_number') }}" required
                                       placeholder="pl. 42"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('billing_house_number')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <h3 style="color: #004E2B; margin: 30px 0 20px 0; font-size: 18px;">Kiszállítási cím</h3>

                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Irányítószám *</label>
                                <input type="text" name="delivery_postal_code" id="delivery-postal-code" 
                                       value="{{ old('delivery_postal_code', session('construction_postal_code')) }}" required
                                       maxlength="4" pattern="[0-9]{4}" placeholder="pl. 2600"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('delivery_postal_code')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Város *</label>
                                <input type="text" name="delivery_city" id="delivery-city" 
                                       value="{{ old('delivery_city', session('construction_city')) }}" required
                                       placeholder="pl. Vác"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('delivery_city')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Utca *</label>
                                <input type="text" name="delivery_street" id="delivery-street" 
                                       value="{{ old('delivery_street', session('construction_street')) }}" required
                                       placeholder="pl. Kossuth utca"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('delivery_street')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Házszám *</label>
                                <input type="text" name="delivery_house_number" id="delivery-house-number" 
                                       value="{{ old('delivery_house_number', session('construction_house_number')) }}" required
                                       placeholder="pl. 42"
                                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">
                                @error('delivery_house_number')
                                    <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Megjegyzés (opcionális)</label>
                            <textarea name="notes" rows="4"
                                      style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" style="width: 100%; padding: 20px; background: #004E2B; color: #fff; border: none; font-size: 20px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                            Megrendelés véglegesítése
                        </button>
                    </form>
                </div>

                <div style="margin-top: 30px; text-align: center;">
                    <a href="{{ route('shop') }}" style="color: #004E2B; text-decoration: underline; font-size: 16px;">
                        ← Vissza a vásárláshoz
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
let deliveryPrice = {{ $deliveryPrice ?? 0 }};
const deliveryPricePerCbm = {{ $deliveryPrice && $totalVolume ? round($deliveryPrice / $totalVolume, 2) : 0 }};
const needsDelivery = {{ session('needs_delivery') ? 'true' : 'false' }};
const pumpFixedFee = {{ $pumpFixedFee ?? 0 }};
const pumpHourlyFee = {{ $pumpHourlyFee ?? 0 }};
const pumpEstimatedHours = {{ $pumpEstimatedHours ?? 0 }};
const pumpTotalFee = pumpFixedFee + (pumpHourlyFee * pumpEstimatedHours);
let subtotalAmount = {{ $total }};

console.log('🚚 Delivery info:', {needsDelivery, deliveryPrice, deliveryPricePerCbm, subtotalAmount});
console.log('🏗️ Pump info:', {pumpFixedFee, pumpHourlyFee, pumpEstimatedHours, pumpTotalFee});

// Show delivery row if delivery is requested
if (needsDelivery && deliveryPrice > 0) {
    console.log('✅ Showing delivery row');
    document.getElementById('delivery-row').style.display = 'table-row';
    const grandTotal = subtotalAmount + deliveryPrice;
    document.getElementById('grand-total').textContent = new Intl.NumberFormat('hu-HU').format(grandTotal) + ' Ft';
} else {
    console.log('❌ Delivery row hidden - needsDelivery:', needsDelivery, 'deliveryPrice:', deliveryPrice);
}

// Mennyiség frissítése
console.log('🔍 Regisztráljuk az input listenereket...');
document.querySelectorAll('.quantity-input').forEach(input => {
    console.log('Input found:', input, 'data:', {
        cartKey: input.dataset.cartKey,
        unitPrice: input.dataset.unitPrice,
        volumeCbm: input.dataset.volumeCbm,
        value: input.value
    });
    
    input.addEventListener('input', function() {
        console.log('🎯 INPUT EVENT FIRED!');
        const cartKey = this.dataset.cartKey;
        const quantity = parseFloat(this.value);
        const unitPrice = parseFloat(this.dataset.unitPrice);
        const volumeCbm = parseFloat(this.dataset.volumeCbm);
        
        console.log('📊 Frissítés adatai:', {cartKey, quantity, unitPrice, volumeCbm});
        
        if (quantity < 0.5 || isNaN(quantity)) {
            console.log('⚠️ Érvénytelen mennyiség:', quantity);
            return;
        }
        
        // Azonnal frissítjük a sor összegét (lokálisan)
        const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
        const itemTotal = row.querySelector('.item-total');
        const newItemTotal = unitPrice * quantity;
        console.log('💰 Új termék összeg:', newItemTotal);
        itemTotal.textContent = new Intl.NumberFormat('hu-HU').format(newItemTotal) + ' Ft';
        
        // Számoljuk az új részösszeget
        let newSubtotal = 0;
        let totalVolume = 0;
        document.querySelectorAll('.quantity-input').forEach(inp => {
            const qty = parseFloat(inp.value) || 0;
            const price = parseFloat(inp.dataset.unitPrice) || 0;
            const vol = parseFloat(inp.dataset.volumeCbm) || 1;
            newSubtotal += price * qty;
            totalVolume += vol * qty;
        });
        
        console.log('📦 Új részösszeg:', newSubtotal, 'Összes köbméter:', totalVolume);
        
        subtotalAmount = newSubtotal;
        document.getElementById('subtotal').textContent = new Intl.NumberFormat('hu-HU').format(newSubtotal) + ' Ft';
        
        // Ha van fuvar, számoljuk újra lokálisan és MUTASSUK MEG a sort
        if (needsDelivery && deliveryPricePerCbm > 0) {
            deliveryPrice = deliveryPricePerCbm * totalVolume;
            console.log('🚚 Új fuvardíj:', deliveryPrice, '(', deliveryPricePerCbm, '×', totalVolume, ')');
            document.getElementById('delivery-total').textContent = new Intl.NumberFormat('hu-HU').format(deliveryPrice) + ' Ft';
            document.getElementById('delivery-row').style.display = 'table-row';
        }
        
        // Frissítjük a végösszeget
        const grandTotal = newSubtotal + (needsDelivery ? deliveryPrice : 0) + pumpTotalFee;
        document.getElementById('grand-total').textContent = new Intl.NumberFormat('hu-HU').format(grandTotal) + ' Ft';
        
        // AJAX kérés a kosár frissítéséhez (háttérben)
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_key: cartKey,
                quantity: quantity
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    console.error('Update error:', errorData);
                    throw new Error(errorData.message || 'Hiba történt');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Kosár frissítve a szerveren');
            if (!data.success) {
                alert('Hiba történt a kosár frissítése közben: ' + (data.message || 'Ismeretlen hiba'));
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

// Tétel törlése
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
        if (!confirm('Biztosan törölni szeretnéd ezt a terméket a kosárból?')) {
            return;
        }
        
        const cartKey = this.dataset.cartKey;
        console.log('Törlés - cartKey:', cartKey);
        
        // AJAX kérés a tétel törléséhez
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_key: cartKey
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response body:', text);
                    throw new Error('Network response was not ok: ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Töröljük a sort
                const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
                row.remove();
                
                // Frissítjük a részösszeget
                const subtotal = document.getElementById('subtotal');
                subtotal.textContent = new Intl.NumberFormat('hu-HU').format(data.grand_total) + ' Ft';
                
                // Update subtotal amount for delivery calculation
                subtotalAmount = data.grand_total;
                
                // Update grand total with delivery if needed
                let finalTotal = data.grand_total;
                if (needsDelivery && deliveryPrice > 0) {
                    finalTotal += deliveryPrice;
                }
                finalTotal += pumpTotalFee;
                document.getElementById('grand-total').textContent = new Intl.NumberFormat('hu-HU').format(finalTotal) + ' Ft';
                
                // Ha üres a kosár, átirányítás
                if (data.cart_empty) {
                    alert('A kosár üres. Átirányítás a boltba...');
                    window.location.href = '{{ route("shop") }}';
                }
            } else {
                alert('Hiba történt a tétel törlése közben: ' + (data.message || 'Ismeretlen hiba'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a tétel törlése közben. Kérjük, frissítse az oldalt és próbálja újra.');
        });
    });
});

// Customer type toggle (Magánszemély / Cég)
const customerTypeToggle = document.getElementById('customer-type-toggle');
const companyFields = document.getElementById('company-fields');
const toggleSlider = document.getElementById('toggle-slider');
const toggleKnob = document.getElementById('toggle-knob');
const companyNameInput = document.getElementById('company-name');
const taxNumberInput = document.getElementById('tax-number');

function updateToggleVisuals(isCompany) {
    if (isCompany) {
        toggleSlider.style.backgroundColor = '#004E2B';
        toggleKnob.style.transform = 'translateX(30px)';
        companyFields.style.display = 'block';
        companyNameInput.required = true;
        taxNumberInput.required = true;
    } else {
        toggleSlider.style.backgroundColor = '#ccc';
        toggleKnob.style.transform = 'translateX(0)';
        companyFields.style.display = 'none';
        companyNameInput.required = false;
        taxNumberInput.required = false;
    }
}

// Initialize on page load
updateToggleVisuals(customerTypeToggle.checked);

customerTypeToggle.addEventListener('change', function() {
    updateToggleVisuals(this.checked);
});
</script>
@endpush
@endsection
