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
                                <a href="{{ route('checkout.index') }}" style="padding: 15px 30px; background: #004E2B; color: #fff; text-decoration: none; font-weight: 500;">
                                    Tovább a pénztárhoz
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
