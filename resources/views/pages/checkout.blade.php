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
                            <tr style="border-top: 2px solid #004E2B;">
                                <td colspan="3" style="text-align: right; padding: 20px 15px; font-size: 20px; font-weight: 700; color: #004E2B;">
                                    Végösszeg:
                                </td>
                                <td style="text-align: right; padding: 20px 15px; font-size: 24px; font-weight: 700; color: #004E2B;" id="grand-total">
                                    {{ number_format($total, 0, ',', ' ') }} Ft
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Vásárlói adatok form -->
                <div style="background: #fff; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="color: #004E2B; margin-bottom: 25px; font-size: 22px;">Adatok megadása</h2>
                    
                    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                        @csrf

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

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Számlázási cím *</label>
                            <textarea name="address" rows="3" required
                                      style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">{{ old('address') }}</textarea>
                            @error('address')
                                <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Kiszállítási cím *</label>
                            <textarea name="delivery_address" rows="3" required
                                      style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px;">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <p style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
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

// Mennyiség frissítése
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const cartKey = this.dataset.cartKey;
        const quantity = parseFloat(this.value);
        
        console.log('Frissítés - cartKey:', cartKey, 'quantity:', quantity);
        
        if (quantity < 0.5) {
            alert('A minimális mennyiség 0.5');
            this.value = 0.5;
            return;
        }
        
        // AJAX kérés a kosár frissítéséhez
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
            console.log('Update response status:', response.status);
            if (!response.ok) {
                return response.json().then(errorData => {
                    console.error('Update error response:', errorData);
                    // Mutassuk a validációs hibát
                    const errorMessage = errorData.message || 'Hiba történt a frissítés közben';
                    alert(errorMessage);
                    throw new Error(errorMessage);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Update response data:', data);
            if (data.success) {
                // Frissítjük a sor összegét
                const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
                const itemTotal = row.querySelector('.item-total');
                itemTotal.textContent = new Intl.NumberFormat('hu-HU').format(data.item_total) + ' Ft';
                
                // Frissítjük a végösszeget
                document.getElementById('grand-total').textContent = new Intl.NumberFormat('hu-HU').format(data.grand_total) + ' Ft';
            } else {
                alert('Hiba történt a kosár frissítése közben: ' + (data.message || 'Ismeretlen hiba'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Visszaállítjuk az eredeti értéket
            location.reload();
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
                
                // Frissítjük a végösszeget
                document.getElementById('grand-total').textContent = new Intl.NumberFormat('hu-HU').format(data.grand_total) + ' Ft';
                
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
</script>
@endpush
@endsection
