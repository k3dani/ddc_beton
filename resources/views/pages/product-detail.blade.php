@extends('layouts.public')

@section('title', $product->name)

@section('content')
<div class="page page-product-detail">
    <div class="top-block" style="background: url('{{ $product->image ? asset('storage/' . $product->image) : 'http://betoonpluss.k3.hu/wp-content/uploads/2025/10/beton-epulet3.jpg' }}') center / cover no-repeat;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ $product->name }}</h1>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="margin: 20px auto; max-width: 1200px; padding: 15px 20px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin: 20px auto; max-width: 1200px; padding: 15px 20px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px;">
            {{ session('error') }}
        </div>
    @endif

    @if($selectedLocation)
        <div style="background: #f8f9fa; border-bottom: 2px solid #004E2B; padding: 15px 0; margin-bottom: 30px;">
            <div class="container-fluid">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: #004E2B; font-size: 18px;">Kiválasztott telephely:</strong> 
                        <span style="font-size: 18px;">{{ $selectedLocation->name }}</span>
                    </div>
                    <a href="{{ route('homepage') }}" style="color: #004E2B; text-decoration: underline; font-size: 14px;">Telephely módosítása</a>
                </div>
            </div>
        </div>
    @endif

    <div class="container-fluid" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="row">
            <div class="col-md-8">
                <div style="background: #fff; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; max-width: 600px; margin-bottom: 30px;">
                    @endif

                    <div style="font-size: 16px; line-height: 1.8; color: #333;">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    @if($product->short_description)
                        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-left: 4px solid #004E2B;">
                            <h3 style="color: #004E2B; margin-bottom: 15px;">Mire van szükség a betonozás során?</h3>
                            {!! nl2br(e($product->short_description)) !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div style="background: #fff; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                    @if($selectedLocation)
                        @php
                            $locationPrice = $product->locations->where('id', $selectedLocation->id)->first();
                        @endphp
                        
                        @if($locationPrice && $locationPrice->pivot->is_available)
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">1m³ ár:</div>
                                <div style="font-size: 32px; font-weight: 700; color: #004E2B; margin-bottom: 5px;">
                                    Bruttó: {{ number_format($locationPrice->pivot->gross_price, 2, ',', ' ') }} Ft
                                </div>
                                <div style="font-size: 18px; color: #666;">
                                    Nettó: {{ number_format($locationPrice->pivot->net_price, 2, ',', ' ') }} Ft
                                </div>
                            </div>

                            <form method="POST" action="{{ route('cart.add', $product->slug) }}" style="margin-bottom: 30px;">
                                @csrf
                                <input type="hidden" name="location_id" value="{{ $selectedLocation->id }}">
                                
                                <div style="margin-bottom: 20px;">
                                    <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #333;">Mennyiség:</label>
                                    <select name="quantity" style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
                                        <option value="0.5">0.5m³</option>
                                        <option value="1">1m³</option>
                                        <option value="1.5">1.5m³</option>
                                        <option value="2">2m³</option>
                                        <option value="2.5">2.5m³</option>
                                        <option value="3">3m³</option>
                                        <option value="3.5">3.5m³</option>
                                        <option value="4">4m³</option>
                                        <option value="4.5">4.5m³</option>
                                        <option value="5">5m³</option>
                                        <option value="6">6m³</option>
                                        <option value="7">7m³</option>
                                        <option value="8">8m³</option>
                                    </select>
                                </div>

                                <div id="calculated-price" style="text-align: center; margin-bottom: 20px; padding: 15px; background: #f8f9fa;">
                                    <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Számított ár:</div>
                                    <div style="font-size: 24px; font-weight: 700; color: #004E2B;" id="total-gross">
                                        {{ number_format($locationPrice->pivot->gross_price * 0.5, 0, ',', ' ') }} Ft
                                    </div>
                                    <div style="font-size: 16px; color: #666;" id="total-net">
                                        Nettó: {{ number_format($locationPrice->pivot->net_price * 0.5, 0, ',', ' ') }} Ft
                                    </div>
                                </div>

                                <button type="submit" style="width: 100%; padding: 18px; background: #004E2B; color: #fff; border: none; font-size: 18px; font-weight: 500; cursor: pointer; transition: all 0.3s; border: 2px solid #004E2B;">
                                    Kosárba tesz
                                </button>
                            </form>
                        @else
                            <div style="padding: 30px; text-align: center; background: #fff3cd; border: 1px solid #ffeaa7; color: #856404;">
                                <p style="margin: 0;">Ez a termék jelenleg nem elérhető a kiválasztott telephelyen.</p>
                            </div>
                        @endif
                    @else
                        <div style="padding: 30px; text-align: center; background: #fff3cd; border: 1px solid #ffeaa7; color: #856404;">
                            <p style="margin-bottom: 15px;">Kérjük, először válasszon telephelyet az ár megtekintéséhez!</p>
                            <a href="{{ route('homepage') }}" style="display: inline-block; padding: 12px 25px; background: #004E2B; color: #fff; text-decoration: none; font-weight: 500;">
                                Telephely választása
                            </a>
                        </div>
                    @endif

                    <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #ddd;">
                        <h5 style="color: #004E2B; margin-bottom: 15px;">További információra lenne szükésge? Keressen minket!</h5>
                        <p style="margin-bottom: 10px;">
                            <a href="mailto:ddcbeton@duna-drava.hu" style="color: #004E2B; text-decoration: underline;">ddcbeton@duna-drava.hu</a>
                        </p>
                        <p>
                            <a href="https://www.ddcbeton.hu/hu/betonuzemeink" target="_blank" style="color: #004E2B; text-decoration: underline; font-size: 14px;">www.ddcbeton.hu/betonuzemeink</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <a href="{{ route('shop') }}" style="display: inline-block; padding: 15px 40px; background: #fff; color: #004E2B; border: 2px solid #004E2B; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    ← Vissza a termékekhez
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="quantity"]')?.addEventListener('change', function() {
    const quantity = parseFloat(this.value);
    const grossPrice = {{ $selectedLocation && $locationPrice && $locationPrice->pivot->is_available ? $locationPrice->pivot->gross_price : 0 }};
    const netPrice = {{ $selectedLocation && $locationPrice && $locationPrice->pivot->is_available ? $locationPrice->pivot->net_price : 0 }};
    
    const totalGross = Math.round(grossPrice * quantity);
    const totalNet = Math.round(netPrice * quantity);
    
    document.getElementById('total-gross').textContent = new Intl.NumberFormat('hu-HU').format(totalGross) + ' Ft';
    document.getElementById('total-net').textContent = 'Nettó: ' + new Intl.NumberFormat('hu-HU').format(totalNet) + ' Ft';
});
</script>
@endpush
@endsection
