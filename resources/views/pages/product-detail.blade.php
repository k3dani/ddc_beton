@extends('layouts.public')

@section('title', $product->name)

@section('content')
<div class="page page-product-detail">
    <div class="top-block" style="background: url('{{ $product->image ? asset('storage/' . $product->image) : '/images/beton-epulet3.jpg' }}') center / cover no-repeat;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ $product->name }}</h1>
                    @if($product->technical_name)
                        <p style="color: #888; font-size: 18px; margin-top: 10px; margin-bottom: 0;">{{ $product->technical_name }}</p>
                    @endif
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
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; max-width: 50%; height: auto; margin-bottom: 30px; object-fit: contain;">
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
                                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">1 {{ $product->unit }} ár:</div>
                                <div style="font-size: 32px; font-weight: 700; color: #004E2B; margin-bottom: 5px;">
                                    Bruttó: {{ number_format($locationPrice->pivot->gross_price, 0, ',', ' ') }} Ft
                                </div>
                                <div style="font-size: 18px; color: #666;">
                                    Nettó: {{ number_format($locationPrice->pivot->net_price, 0, ',', ' ') }} Ft
                                </div>
                            </div>

                            <form method="POST" action="{{ route('cart.add', $product->slug) }}" id="add-to-cart-form" style="margin-bottom: 30px;">
                                @csrf
                                <input type="hidden" name="location_id" value="{{ $selectedLocation->id }}">
                                
                                <div style="margin-bottom: 20px;">
                                    <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #333;">Mennyiség ({{ $product->unit }}):</label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity-input"
                                           value="1" 
                                           min="0.5" 
                                           step="0.5"
                                           style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
                                    <button type="button" id="open-calculator-btn" style="margin-top: 10px; padding: 8px 15px; background: #6c757d; color: #fff; border: none; font-size: 13px; cursor: pointer; border-radius: 4px; width: 100%;">
                                        📊 Segítség a mennyiség meghatározásához
                                    </button>
                                </div>

                                <div id="calculated-price" style="text-align: center; margin-bottom: 20px; padding: 15px; background: #f8f9fa;">
                                    <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Számított ár:</div>
                                    <div style="font-size: 24px; font-weight: 700; color: #004E2B;" id="total-gross">
                                        {{ number_format($locationPrice->pivot->gross_price * 1, 0, ',', ' ') }} Ft
                                    </div>
                                    <div style="font-size: 16px; color: #666;" id="total-net">
                                        Nettó: {{ number_format($locationPrice->pivot->net_price * 1, 0, ',', ' ') }} Ft
                                    </div>
                                </div>

                                <button type="button" id="order-btn" style="width: 100%; padding: 18px; background: #004E2B; color: #fff; border: none; font-size: 18px; font-weight: 500; cursor: pointer; transition: all 0.3s; border: 2px solid #004E2B;">
                                    Megrendel
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

<!-- Modal -->
<div id="order-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);">
    <div style="background-color: #fff; margin: 10% auto; padding: 40px; border: none; width: 90%; max-width: 500px; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <button id="close-modal" style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 28px; font-weight: 700; color: #666; cursor: pointer; line-height: 1;">&times;</button>
        <h2 style="color: #004E2B; margin-bottom: 30px; font-size: 24px; text-align: center;">Mi a következő lépés?</h2>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <button id="continue-shopping" style="width: 100%; padding: 18px; background: #fff; color: #004E2B; border: 2px solid #004E2B; font-size: 18px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                Folytatom a vásárlást
            </button>
            <button id="finalize-order" style="width: 100%; padding: 18px; background: #004E2B; color: #fff; border: 2px solid #004E2B; font-size: 18px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                Véglegesítem a rendelést
            </button>
        </div>
    </div>
</div>

<!-- Delivery Service Modal -->
<div id="delivery-modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7);">
    <div style="background-color: #fff; margin: 5% auto; padding: 40px; border: none; width: 90%; max-width: 700px; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <button id="close-delivery-modal" style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 28px; font-weight: 700; color: #666; cursor: pointer; line-height: 1;">&times;</button>
        
        <h2 style="color: #004E2B; margin-bottom: 15px; font-size: 24px;">Kiegészítő szolgáltatások</h2>
        <p style="color: #666; margin-bottom: 30px; font-size: 15px;">Válassza ki, mely szolgáltatásokat igényli a beton szállítása mellett:</p>
        
        <div id="delivery-content">
            <!-- FUVAR SZOLGÁLTATÁS -->
            <div style="border: 2px solid #004E2B; padding: 25px; margin-bottom: 20px; border-radius: 8px; background: #f8fdf9;">
                <h3 style="color: #004E2B; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">🚚 Fuvar szolgáltatás</h3>
                <p style="font-size: 15px; margin-bottom: 15px; color: #666;">Kér-e fuvar szolgáltatást az építkezési címére?</p>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #666;">Fuvar:</label>
                    <select id="delivery-select" style="width: 100%; padding: 12px; border: 2px solid #004E2B; border-radius: 4px; font-size: 16px; font-weight: 500;">
                        <option value="">-- Válasszon --</option>
                        <option value="yes">Igen, kérek fuvar szolgáltatást</option>
                        <option value="no">Nem, saját szállítással oldanám meg</option>
                    </select>
                </div>
            
                <div id="delivery-info" style="display: none; background: #f0f8f4; padding: 15px; margin-bottom: 15px; border-left: 4px solid #004E2B;">
                    <p style="margin: 5px 0; color: #666;"><strong>Távolság:</strong> <span id="distance-display"></span> km</p>
                    <p style="margin: 5px 0; color: #666;"><strong>Mennyiség:</strong> <span id="volume-display"></span> m³</p>
                    <p style="margin: 15px 0 5px 0; font-size: 18px; font-weight: 700; color: #004E2B;"><strong>Szállítási díj:</strong> <span id="delivery-price-display"></span> Ft</p>
                </div>
                
                <div id="delivery-error" style="display: none; background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; color: #856404;">
                    <p style="margin: 0;"></p>
                </div>
            </div>

            <!-- PUMPA SZOLGÁLTATÁS -->
            <div style="border: 2px solid #004E2B; padding: 25px; margin-bottom: 20px; border-radius: 8px; background: #f8fdf9;">
                <h3 style="color: #004E2B; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">🏗️ Pumpa szolgáltatás</h3>
                <p style="font-size: 15px; margin-bottom: 15px; color: #666;">Kér-e betonpumpa szolgáltatást? (A betonpumpa segítségével nehezen megközelíthető helyekre is könnyedén eljuttathatja a betont.)</p>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #666;">Válasszon pumpát:</label>
                    <select id="pump-select" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 4px; font-size: 16px;">
                        <option value="">-- Nem kérek pumpa szolgáltatást --</option>
                    </select>
                </div>
                
                <div id="pump-details" style="display: none; background: #f0f8f4; padding: 15px; margin-bottom: 15px; border-left: 4px solid #004E2B;">
                    <p style="margin: 5px 0; color: #666;"><strong>Gémhossz:</strong> <span id="pump-boom-display"></span> m</p>
                    <p style="margin: 5px 0; color: #666;"><strong>Fix díj:</strong> <span id="pump-fixed-fee-display"></span> Ft</p>
                    <p style="margin: 5px 0 15px 0; color: #666; font-size: 13px;"><em>Óradíj: <span id="pump-hourly-fee-display"></span> Ft/óra</em></p>
                    
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #c8e6c9;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #004E2B;">Becsült használati idő (órák): *</label>
                        <input type="number" id="pump-hours" min="1" step="0.5" value="2" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 16px;">
                        <p style="margin: 8px 0 0 0; color: #004E2B; font-size: 13px;"><strong>Becsült óradíj:</strong> <span id="pump-hour-total"></span> Ft (<span id="pump-hours-display">2</span> óra × <span id="pump-hourly-rate"></span> Ft/óra)</p>
                    </div>
                </div>
                
                <div id="pump-error" style="display: none; background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; color: #856404;">
                    <p style="margin: 0;"></p>
                </div>
            </div>

            <!-- VÉGLEGESÍTÉS GOMB -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e0e0e0; margin-top: 10px;">
                <button onclick="deliveryModal.style.display='none'" style="padding: 12px 30px; background: #fff; color: #666; border: 2px solid #ccc; cursor: pointer; font-weight: 500; border-radius: 4px;">
                    Mégse
                </button>
                <button id="finalize-order-btn" style="padding: 12px 40px; background: #004E2B; color: #fff; border: 2px solid #004E2B; cursor: pointer; font-weight: 500; font-size: 16px; border-radius: 4px;">
                    Tovább a rendeléshez
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Concrete Calculator Modal -->
<div id="calculator-modal" style="display: none; position: fixed; z-index: 10001; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7);">
    <div style="background-color: #fff; margin: 3% auto; padding: 40px; border: none; width: 90%; max-width: 800px; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.3); border-radius: 8px;">
        <button id="close-calculator-modal" style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 28px; font-weight: 700; color: #666; cursor: pointer; line-height: 1;">&times;</button>
        
        <h2 style="color: #004E2B; margin-bottom: 10px; font-size: 26px;">📊 Betonmennyiség Kalkulátor</h2>
        <p style="color: #666; margin-bottom: 30px; font-size: 15px;">Válasszon geometriai alakzatot és adja meg a méreteket a szükséges betonmennyiség kiszámításához.</p>
        
        <!-- Shape Selection -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 15px; font-weight: 600; color: #333; font-size: 16px;">Válasszon alakzatot:</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <button type="button" class="shape-btn" data-shape="rectangle" style="padding: 15px; background: #f8f9fa; border: 2px solid #ddd; cursor: pointer; border-radius: 8px; transition: all 0.3s; text-align: center;">
                    <div style="font-size: 32px; margin-bottom: 8px;">⬜</div>
                    <div style="font-weight: 600; color: #333;">Téglalap / Négyzet</div>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">Alapozás, födém, fal</div>
                </button>
                <button type="button" class="shape-btn" data-shape="circle" style="padding: 15px; background: #f8f9fa; border: 2px solid #ddd; cursor: pointer; border-radius: 8px; transition: all 0.3s; text-align: center;">
                    <div style="font-size: 32px; margin-bottom: 8px;">⭕</div>
                    <div style="font-weight: 600; color: #333;">Kör / Henger</div>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">Oszlop, cölöp, kút</div>
                </button>
                <button type="button" class="shape-btn" data-shape="trapezoid" style="padding: 15px; background: #f8f9fa; border: 2px solid #ddd; cursor: pointer; border-radius: 8px; transition: all 0.3s; text-align: center;">
                    <div style="font-size: 32px; margin-bottom: 8px;">🔶</div>
                    <div style="font-weight: 600; color: #333;">Trapéz</div>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">Lépcsők, lejtős fal</div>
                </button>
            </div>
        </div>

        <!-- Rectangle Form -->
        <div id="rectangle-form" class="shape-form" style="display: none; background: #f8fdf9; padding: 25px; border-radius: 8px; border: 2px solid #004E2B;">
            <h3 style="color: #004E2B; margin-bottom: 20px; font-size: 18px;">⬜ Téglalap / Négyzet alapú térfogat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Hosszúság (m):</label>
                    <input type="number" id="rect-length" step="0.01" min="0" placeholder="pl. 10.5" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Szélesség (m):</label>
                    <input type="number" id="rect-width" step="0.01" min="0" placeholder="pl. 5" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Magasság (m):</label>
                    <input type="number" id="rect-height" step="0.01" min="0" placeholder="pl. 0.3" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
            </div>
            <div style="text-align: center; padding: 15px; background: #e8f5e9; border-radius: 4px; margin-bottom: 15px;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Számítás: Hossz × Szélesség × Magasság</div>
                <div id="rect-result" style="font-size: 24px; font-weight: 700; color: #004E2B;">0 m³</div>
            </div>
            <button type="button" onclick="applyCalculatedVolume('rectangle')" style="width: 100%; padding: 15px; background: #004E2B; color: #fff; border: none; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 4px;">
                ✓ Alkalmaz
            </button>
        </div>

        <!-- Circle Form -->
        <div id="circle-form" class="shape-form" style="display: none; background: #f8fdf9; padding: 25px; border-radius: 8px; border: 2px solid #004E2B;">
            <h3 style="color: #004E2B; margin-bottom: 20px; font-size: 18px;">⭕ Kör / Henger alapú térfogat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Átmérő (m):</label>
                    <input type="number" id="circle-diameter" step="0.01" min="0" placeholder="pl. 0.5" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">vagy sugár × 2</div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Magasság (m):</label>
                    <input type="number" id="circle-height" step="0.01" min="0" placeholder="pl. 3" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
            </div>
            <div style="text-align: center; padding: 15px; background: #e8f5e9; border-radius: 4px; margin-bottom: 15px;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Számítás: π × (Átmérő/2)² × Magasság</div>
                <div id="circle-result" style="font-size: 24px; font-weight: 700; color: #004E2B;">0 m³</div>
            </div>
            <button type="button" onclick="applyCalculatedVolume('circle')" style="width: 100%; padding: 15px; background: #004E2B; color: #fff; border: none; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 4px;">
                ✓ Alkalmaz
            </button>
        </div>

        <!-- Trapezoid Form -->
        <div id="trapezoid-form" class="shape-form" style="display: none; background: #f8fdf9; padding: 25px; border-radius: 8px; border: 2px solid #004E2B;">
            <h3 style="color: #004E2B; margin-bottom: 20px; font-size: 18px;">🔶 Trapéz alapú térfogat</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Felső oldal (m):</label>
                    <input type="number" id="trap-top" step="0.01" min="0" placeholder="pl. 2" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Alsó oldal (m):</label>
                    <input type="number" id="trap-bottom" step="0.01" min="0" placeholder="pl. 4" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Magasság (m):</label>
                    <input type="number" id="trap-height" step="0.01" min="0" placeholder="pl. 1" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Hosszúság (m):</label>
                    <input type="number" id="trap-length" step="0.01" min="0" placeholder="pl. 5" style="width: 100%; padding: 10px; border: 2px solid #004E2B; border-radius: 4px; font-size: 15px;">
                </div>
            </div>
            <div style="text-align: center; padding: 15px; background: #e8f5e9; border-radius: 4px; margin-bottom: 15px;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Számítás: ((Felső + Alsó) / 2) × Magasság × Hosszúság</div>
                <div id="trap-result" style="font-size: 24px; font-weight: 700; color: #004E2B;">0 m³</div>
            </div>
            <button type="button" onclick="applyCalculatedVolume('trapezoid')" style="width: 100%; padding: 15px; background: #004E2B; color: #fff; border: none; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 4px;">
                ✓ Alkalmaz
            </button>
        </div>

        <!-- Initial message when no shape selected -->
        <div id="no-shape-message" style="text-align: center; padding: 40px; color: #666; font-size: 16px;">
            👆 Válasszon egy alakzatot a számítás megkezdéséhez
        </div>
    </div>
</div>

<!-- Address Modal (ugyanaz mint a homepage-en) -->
<div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 40px; max-width: 700px; width: 90%; border-radius: 0; position: relative;">
        <h3 style="font-size: 28px; font-weight: 700; color: #004E2B; margin-bottom: 20px; font-family: 'Yantramanav', sans-serif;">Építkezési cím megadása</h3>
        <p style="font-size: 16px; margin-bottom: 30px; color: #666;">Kérjük, adja meg az építkezés címét. Ezt a rendelés során fogjuk használni a fuvar díjának kiszámításához.</p>
        
        <form id="addressForm">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Irányítószám:</label>
                    <input type="text" 
                           id="postal-code" 
                           name="postal_code"
                           placeholder="pl. 2600"
                           maxlength="4"
                           pattern="[0-9]{4}"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Város:</label>
                    <input type="text" 
                           id="city" 
                           name="city"
                           placeholder="pl. Vác"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Közterület neve:</label>
                    <input type="text" 
                           id="street-name" 
                           name="street_name"
                           placeholder="pl. Köztársaság"
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Jellege:</label>
                    <select id="street-type" 
                            name="street_type"
                            style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0; background: white;">
                        <option value="">Válassz</option>
                        <option value="utca">utca</option>
                        <option value="út">út</option>
                        <option value="tér">tér</option>
                        <option value="köz">köz</option>
                        <option value="körút">körút</option>
                        <option value="sétány">sétány</option>
                        <option value="dűlő">dűlő</option>
                        <option value="sor">sor</option>
                        <option value="park">park</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Házszám:</label>
                <input type="text" 
                       id="house-number" 
                       name="house_number"
                       placeholder="pl. 52. vagy 52/A"
                       style="width: 100%; padding: 12px; border: 2px solid #ddd; font-size: 16px; border-radius: 0;">
            </div>
            
            <input type="hidden" id="construction-latitude">
            <input type="hidden" id="construction-longitude">
            
            <div id="distance-info" style="display: none; margin-bottom: 20px; padding: 15px; background: #e8f5e9; border-left: 4px solid #004E2B;">
                <p style="margin: 0; color: #333;"><strong>Távolság a telephelytől:</strong> <span id="distance-value"></span> km</p>
            </div>
            
            <div id="distance-warning" style="display: none; margin-bottom: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ff9800; color: #856404; font-size: 15px;">
                <!-- Warning message will be inserted here -->
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button type="button" 
                        id="save-address-btn-product"
                        onclick="handleAddressSaveForProduct(event)"
                        style="flex: 1; padding: 15px 30px; background: #004E2B; color: white; border: 2px solid #004E2B; font-size: 18px; font-weight: 600; cursor: pointer; border-radius: 0; transition: all 0.3s;">
                    Mentés és tovább
                </button>
                <!-- Kihagyom gomb eltávolítva: szállítás esetén kötelező a cím megadása -->
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ============================================
// CONCRETE CALCULATOR MODAL
// ============================================

const calculatorModal = document.getElementById('calculator-modal');
const openCalculatorBtn = document.getElementById('open-calculator-btn');
const closeCalculatorModal = document.getElementById('close-calculator-modal');
const shapeButtons = document.querySelectorAll('.shape-btn');
const shapeForms = document.querySelectorAll('.shape-form');

let currentCalculatedVolume = 0;

// Open calculator modal
openCalculatorBtn?.addEventListener('click', function() {
    calculatorModal.style.display = 'block';
});

// Close calculator modal
closeCalculatorModal?.addEventListener('click', function() {
    calculatorModal.style.display = 'none';
});

// Close on outside click
window.addEventListener('click', function(event) {
    if (event.target === calculatorModal) {
        calculatorModal.style.display = 'none';
    }
});

// Shape selection
shapeButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        const shape = this.getAttribute('data-shape');
        
        // Remove active state from all buttons
        shapeButtons.forEach(b => {
            b.style.background = '#f8f9fa';
            b.style.borderColor = '#ddd';
        });
        
        // Add active state to clicked button
        this.style.background = '#e8f5e9';
        this.style.borderColor = '#004E2B';
        
        // Hide all forms
        shapeForms.forEach(form => form.style.display = 'none');
        document.getElementById('no-shape-message').style.display = 'none';
        
        // Show selected form
        document.getElementById(shape + '-form').style.display = 'block';
    });
});

// Rectangle calculations
const rectLength = document.getElementById('rect-length');
const rectWidth = document.getElementById('rect-width');
const rectHeight = document.getElementById('rect-height');
const rectResult = document.getElementById('rect-result');

function calculateRectangle() {
    const length = parseFloat(rectLength.value) || 0;
    const width = parseFloat(rectWidth.value) || 0;
    const height = parseFloat(rectHeight.value) || 0;
    
    const volume = length * width * height;
    rectResult.textContent = volume.toFixed(2) + ' m³';
    currentCalculatedVolume = volume;
}

rectLength?.addEventListener('input', calculateRectangle);
rectWidth?.addEventListener('input', calculateRectangle);
rectHeight?.addEventListener('input', calculateRectangle);

// Circle calculations
const circleDiameter = document.getElementById('circle-diameter');
const circleHeight = document.getElementById('circle-height');
const circleResult = document.getElementById('circle-result');

function calculateCircle() {
    const diameter = parseFloat(circleDiameter.value) || 0;
    const height = parseFloat(circleHeight.value) || 0;
    const radius = diameter / 2;
    
    const volume = Math.PI * Math.pow(radius, 2) * height;
    circleResult.textContent = volume.toFixed(2) + ' m³';
    currentCalculatedVolume = volume;
}

circleDiameter?.addEventListener('input', calculateCircle);
circleHeight?.addEventListener('input', calculateCircle);

// Trapezoid calculations
const trapTop = document.getElementById('trap-top');
const trapBottom = document.getElementById('trap-bottom');
const trapHeight = document.getElementById('trap-height');
const trapLength = document.getElementById('trap-length');
const trapResult = document.getElementById('trap-result');

function calculateTrapezoid() {
    const top = parseFloat(trapTop.value) || 0;
    const bottom = parseFloat(trapBottom.value) || 0;
    const height = parseFloat(trapHeight.value) || 0;
    const length = parseFloat(trapLength.value) || 0;
    
    const volume = ((top + bottom) / 2) * height * length;
    trapResult.textContent = volume.toFixed(2) + ' m³';
    currentCalculatedVolume = volume;
}

trapTop?.addEventListener('input', calculateTrapezoid);
trapBottom?.addEventListener('input', calculateTrapezoid);
trapHeight?.addEventListener('input', calculateTrapezoid);
trapLength?.addEventListener('input', calculateTrapezoid);

// Apply calculated volume to quantity input
function applyCalculatedVolume(shape) {
    if (currentCalculatedVolume > 0) {
        const quantityInput = document.getElementById('quantity-input');
        if (quantityInput) {
            quantityInput.value = currentCalculatedVolume.toFixed(2);
            
            // Trigger input event to update price
            const event = new Event('input', { bubbles: true });
            quantityInput.dispatchEvent(event);
            
            // Close modal
            calculatorModal.style.display = 'none';
            
            // Show success message
            alert('✓ Számított mennyiség alkalmazva: ' + currentCalculatedVolume.toFixed(2) + ' m³');
        }
    } else {
        alert('Kérjük, adja meg az összes méretet a számításhoz!');
    }
}

// Make function globally accessible
window.applyCalculatedVolume = applyCalculatedVolume;

// ============================================
// QUANTITY AND PRICE CALCULATION
// ============================================

document.querySelector('input[name="quantity"]')?.addEventListener('input', function() {
    const quantity = parseFloat(this.value) || 0;
    const grossPrice = {{ $selectedLocation && $locationPrice && $locationPrice->pivot->is_available ? $locationPrice->pivot->gross_price : 0 }};
    const netPrice = {{ $selectedLocation && $locationPrice && $locationPrice->pivot->is_available ? $locationPrice->pivot->net_price : 0 }};
    
    const totalGross = Math.round(grossPrice * quantity);
    const totalNet = Math.round(netPrice * quantity);
    
    document.getElementById('total-gross').textContent = new Intl.NumberFormat('hu-HU').format(totalGross) + ' Ft';
    document.getElementById('total-net').textContent = 'Nettó: ' + new Intl.NumberFormat('hu-HU').format(totalNet) + ' Ft';
});

// Modal kezelés
const modal = document.getElementById('order-modal');
const orderBtn = document.getElementById('order-btn');
const closeModal = document.getElementById('close-modal');
const continueShoppingBtn = document.getElementById('continue-shopping');
const finalizeOrderBtn = document.getElementById('finalize-order');
const form = document.getElementById('add-to-cart-form');

orderBtn?.addEventListener('click', function() {
    // Hozzáadjuk a kosárhoz AJAX-szal
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Modal megjelenítése
        modal.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback: submit a form
        form.submit();
    });
});

closeModal?.addEventListener('click', function() {
    modal.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

continueShoppingBtn?.addEventListener('click', function() {
    window.location.href = '{{ route("shop") }}';
});

finalizeOrderBtn?.addEventListener('click', function() {
    // Modal bezárása
    modal.style.display = 'none';
    
    // Fuvar modal megnyitása
    openDeliveryModal();
});

// Delivery modal kezelés
const deliveryModal = document.getElementById('delivery-modal');
const closeDeliveryModal = document.getElementById('close-delivery-modal');
const deliverySelectDropdown = document.getElementById('delivery-select');
const finalizeOrderModalBtn = document.getElementById('finalize-order-btn');
const pumpSelect = document.getElementById('pump-select');

let availablePumps = [];
let selectedPumpId = null;

function openDeliveryModal() {
    deliveryModal.style.display = 'block';
    
    // Pumpa lista betöltése
    loadAvailablePumps();
    
    // Fuvar díj számítása
    fetch('/delivery/calculate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delivery calculation:', data);
        if (data.success) {
            document.getElementById('distance-display').textContent = data.distance;
            document.getElementById('volume-display').textContent = data.volume;
            document.getElementById('delivery-price-display').textContent = new Intl.NumberFormat('hu-HU').format(data.price);
            document.getElementById('delivery-info').style.display = 'block';
            document.getElementById('delivery-error').style.display = 'none';
        } else {
            document.getElementById('delivery-error').querySelector('p').textContent = data.message || 'Nem sikerült kiszámítani a fuvar díjat.';
            document.getElementById('delivery-error').style.display = 'block';
            document.getElementById('delivery-info').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('delivery-error').querySelector('p').textContent = 'Hiba történt a fuvar díj számítása során.';
        document.getElementById('delivery-error').style.display = 'block';
        document.getElementById('delivery-info').style.display = 'none';
    });
}

closeDeliveryModal?.addEventListener('click', function() {
    deliveryModal.style.display = 'none';
});

// Pump select handler
pumpSelect?.addEventListener('change', function() {
    const pumpId = parseInt(this.value);
    if (pumpId) {
        const pump = availablePumps.find(p => p.id === pumpId);
        if (pump) {
            selectedPumpId = pumpId;
            document.getElementById('pump-boom-display').textContent = pump.boom_length;
            document.getElementById('pump-fixed-fee-display').textContent = new Intl.NumberFormat('hu-HU').format(pump.fixed_fee);
            document.getElementById('pump-hourly-fee-display').textContent = new Intl.NumberFormat('hu-HU').format(pump.hourly_fee);
            document.getElementById('pump-hourly-rate').textContent = new Intl.NumberFormat('hu-HU').format(pump.hourly_fee);
            
            // Calculate initial hour total
            const hours = parseFloat(document.getElementById('pump-hours').value) || 2;
            const hourTotal = pump.hourly_fee * hours;
            document.getElementById('pump-hour-total').textContent = new Intl.NumberFormat('hu-HU').format(hourTotal);
            document.getElementById('pump-hours-display').textContent = hours;
            
            document.getElementById('pump-details').style.display = 'block';
            document.getElementById('pump-error').style.display = 'none';
        }
    } else {
        selectedPumpId = null;
        document.getElementById('pump-details').style.display = 'none';
    }
});

// Pump hours input handler
const pumpHoursInput = document.getElementById('pump-hours');
pumpHoursInput?.addEventListener('input', function() {
    const hours = parseFloat(this.value) || 0;
    const pumpId = parseInt(pumpSelect.value);
    
    if (pumpId && hours > 0) {
        const pump = availablePumps.find(p => p.id === pumpId);
        if (pump) {
            const hourTotal = pump.hourly_fee * hours;
            document.getElementById('pump-hour-total').textContent = new Intl.NumberFormat('hu-HU').format(hourTotal);
            document.getElementById('pump-hours-display').textContent = hours;
        }
    }
});

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
            pumpSelect.innerHTML = '<option value="">-- Nem kérek pumpa szolgáltatást --</option>';
            
            data.pumps.forEach(pump => {
                const option = document.createElement('option');
                option.value = pump.id;
                option.textContent = `${pump.type} (${pump.boom_length}m gémhossz) - ${new Intl.NumberFormat('hu-HU').format(pump.fixed_fee)} Ft`;
                pumpSelect.appendChild(option);
            });
        } else {
            document.getElementById('pump-error').querySelector('p').textContent = data.message || 'Ezen a telephelyen nincs elérhető pumpa.';
            document.getElementById('pump-error').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error loading pumps:', error);
        document.getElementById('pump-error').querySelector('p').textContent = 'Hiba történt a pumpák betöltése során.';
        document.getElementById('pump-error').style.display = 'block';
    });
}

async function savePumpChoice() {
    if (!selectedPumpId) {
        return Promise.resolve({ success: true });
    }
    
    const estimatedHours = parseFloat(document.getElementById('pump-hours').value) || 2;
    
    if (estimatedHours <= 0) {
        alert('Kérjük, adja meg a becsült használati időt!');
        return Promise.reject('No hours specified');
    }
    
    return fetch('/pump/set-choice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            pump_id: selectedPumpId,
            estimated_hours: estimatedHours
        })
    }).then(r => r.json());
}

// Handle delivery dropdown change
deliverySelectDropdown?.addEventListener('change', function() {
    const value = this.value;
    
    if (value === 'yes') {
        // Show delivery info
        document.getElementById('delivery-info').style.display = 'block';
    } else {
        document.getElementById('delivery-info').style.display = 'none';
    }
});

// Handle finalize order button
finalizeOrderModalBtn?.addEventListener('click', async function() {
    try {
        const deliveryChoice = deliverySelectDropdown?.value;
        
        // Validate delivery selection
        if (!deliveryChoice) {
            alert('Kérjük, válassza ki, hogy kér-e fuvar szolgáltatást!');
            return;
        }
        
        // Save pump choice if pump is selected
        const selectedPump = document.getElementById('pump-select')?.value;
        const estimatedHours = parseFloat(document.getElementById('pump-hours')?.value || 0);
        
        if (selectedPump && estimatedHours > 0) {
            await savePumpChoice();
        } else if (selectedPump && estimatedHours <= 0) {
            alert('Kérjük, adjon meg egy becsült óraszámot a pumpa használatához (minimum 0,5 óra).');
            return;
        }

        // Handle delivery choice
        if (deliveryChoice === 'yes') {
            // Check if construction address exists
            const checkAddressResponse = await fetch('/delivery/check-address', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const addressData = await checkAddressResponse.json();
            console.log('Address check result:', addressData);

            if (!addressData.has_address) {
                // No address, save delivery=true and show address modal
                await fetch('/delivery/set-choice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ needs_delivery: true })
                });
                
                console.log('❌ No address found, showing address modal');
                deliveryModal.style.display = 'none';
                openAddressModal();
                return;
            }

            // Address exists, save delivery=true
            await fetch('/delivery/set-choice', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ needs_delivery: true })
            });
            
            console.log('✓ Address exists, proceeding to checkout with delivery');
        } else {
            // No delivery
            await fetch('/delivery/set-choice', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ needs_delivery: false })
            });
            
            console.log('No delivery requested');
        }

        // Redirect to checkout
        window.location.replace('{{ route("checkout.index") }}');

    } catch (error) {
        console.error('Error:', error);
        alert('Hiba történt a rendelés feldolgozása során.');
    }
});

// Address Modal Functions (from homepage)
const selectedLocation = {!! json_encode(session('selected_location')) !!};

// Geocoding - cím koordinátákká alakítása
async function geocodeAddress(addressString) {
    console.log('🔍 Geocoding address:', addressString);
    
    if (typeof google === 'undefined' || !google.maps) {
        console.error('❌ Google Maps API not loaded!');
        return null;
    }
    
    try {
        const geocoder = new google.maps.Geocoder();
        
        const result = await new Promise((resolve, reject) => {
            geocoder.geocode({ 
                address: addressString + ', Magyarország',
                region: 'HU',
                componentRestrictions: {
                    country: 'HU'
                }
            }, (results, status) => {
                console.log('Geocoding status:', status);
                
                if (status === 'OK' && results[0]) {
                    console.log('✓ Geocoding successful:', results[0]);
                    resolve(results[0]);
                } else {
                    console.error('❌ Geocoding failed:', status);
                    reject(new Error('Geocoding failed: ' + status));
                }
            });
        });
        
        const coords = {
            lat: result.geometry.location.lat(),
            lng: result.geometry.location.lng()
        };
        
        console.log('✓ Coordinates:', coords);
        return coords;
    } catch (error) {
        console.error('❌ Geocoding error:', error);
        return null;
    }
}

// Távolság számítása ÚTVONAL alapján (Google Maps Distance Matrix API)
async function calculateDistance(lat, lng, location) {
    const locationLat = parseFloat(location.latitude);
    const locationLng = parseFloat(location.longitude);
    
    console.log('📏 Calculating road distance using Google Maps...');
    console.log('   From:', locationLat, locationLng, '(' + location.name + ')');
    console.log('   To:', lat, lng);
    
    try {
        const service = new google.maps.DistanceMatrixService();
        
        const result = await new Promise((resolve, reject) => {
            service.getDistanceMatrix({
                origins: [{ lat: locationLat, lng: locationLng }],
                destinations: [{ lat: lat, lng: lng }],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false
            }, (response, status) => {
                console.log('Distance Matrix API status:', status);
                console.log('Distance Matrix API response:', response);
                
                if (status === 'OK') {
                    resolve(response);
                } else {
                    console.error('❌ Distance Matrix API error status:', status);
                    reject(new Error('Distance Matrix API failed: ' + status));
                }
            });
        });
        
        const element = result.rows[0].elements[0];
        console.log('Distance element:', element);
        
        if (element.status === 'OK') {
            const distanceInMeters = element.distance.value;
            const distance = distanceInMeters / 1000;
            const duration = element.duration.text;
            
            console.log('✓ Road distance:', distance.toFixed(1), 'km');
            console.log('⏱️ Travel time:', duration);
            
            document.getElementById('distance-value').textContent = distance.toFixed(1);
            document.getElementById('distance-info').style.display = 'block';
            
            const warningDiv = document.getElementById('distance-warning');
            if (distance > 120) {
                warningDiv.innerHTML = '<strong>Figyelem!</strong> Az építési cím több mint 120 km távolságra van a kiválasztott telephelytől (' + distance.toFixed(1) + ' km autóval). Ez különdíjat vonhat magával.';
                warningDiv.style.display = 'block';
                console.warn('⚠️ Distance warning shown:', distance.toFixed(1), 'km > 120 km');
            } else {
                warningDiv.style.display = 'none';
                console.log('✓ Distance OK:', distance.toFixed(1), 'km <= 120 km');
            }
            
            return distance;
        } else {
            console.error('❌ Distance calculation failed:', element.status);
            return null;
        }
    } catch (error) {
        console.error('❌ Error calculating distance:', error);
        return null;
    }
}

// Address save handler for product page
async function handleAddressSaveForProduct(event) {
    console.log('🎯 SAVE ADDRESS BUTTON CLICKED (Product Page)!');
    event.preventDefault();
    event.stopPropagation();

    const postalCode = document.getElementById('postal-code').value.trim();
    const city = document.getElementById('city').value.trim();
    const streetName = document.getElementById('street-name').value.trim();
    const streetType = document.getElementById('street-type').value;
    const houseNumber = document.getElementById('house-number').value.trim();

    console.log('📝 Form values:', { postalCode, city, streetName, streetType, houseNumber });

    if (!postalCode && !city && !streetName) {
        console.log('⏭️ No address provided, skipping');
        skipAddressModalForProduct();
        return;
    }

    let fullAddress = '';
    if (postalCode) fullAddress += postalCode + ' ';
    if (city) fullAddress += city + ', ';
    if (streetName) fullAddress += streetName + ' ';
    if (streetType) fullAddress += streetType + ' ';
    if (houseNumber) fullAddress += houseNumber;

    fullAddress = fullAddress.trim();
    console.log('📍 Full address:', fullAddress);

    const coords = await geocodeAddress(fullAddress);
    let lat = null;
    let lng = null;

    if (!coords) {
        alert('Hiba: A cím nem található a térképen! Kérjük, adja meg pontosan a címet, vagy válasszon a Google javaslatai közül.');
        console.error('❌ Geocoding failed for address:', fullAddress);
        return;
    }

    lat = coords.lat;
    lng = coords.lng;
    console.log('✓ Geocoded address:', fullAddress, 'to', lat, lng);
    console.log('📍 Selected location:', selectedLocation);

    if (!lat || !lng) {
        alert('Hiba: A cím koordinátái hiányoznak!');
        console.error('❌ Hiányzó koordináták:', { lat, lng });
        return;
    }

    if (selectedLocation && selectedLocation.latitude && selectedLocation.longitude) {
        console.log('📏 Calculating distance...');
        const distance = await calculateDistance(lat, lng, selectedLocation);

        if (distance) {
            console.log('📏 Calculated distance:', distance.toFixed(1), 'km');

            if (distance > 120) {
                const warningText = 'Figyelem! Az építési cím több mint 120 km távolságra van a kiválasztott telephelytől (' + distance.toFixed(1) + ' km autóval). Ez különdíjat vonhat magával.';
                alert(warningText);
                console.warn('⚠️ Distance warning shown:', distance.toFixed(1), 'km');
            }
        } else {
            console.error('❌ Distance calculation returned null');
        }
    } else {
        console.error('❌ Selected location missing coordinates:', selectedLocation);
    }

    console.log('💾 Saving address to session...');

    try {
        if (lat && lng && fullAddress) {
            console.log('🏗️ Saving construction address...');
            const response = await fetch('/location/save-address', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    construction_address: fullAddress,
                    construction_latitude: lat,
                    construction_longitude: lng,
                    postal_code: postalCode,
                    city: city,
                    street: (streetName + ' ' + streetType).trim(),
                    house_number: houseNumber
                })
            });

            const data = await response.json();
            console.log('✓ Address saved:', data);
            if (!data.success) {
                alert(data.message || 'Hiba: A cím mentése nem sikerült!');
                return;
            }
        } else {
            alert('Hiba: A cím vagy a koordináták hiányoznak!');
            return;
        }

        // Set delivery choice and redirect to checkout
        const deliveryResponse = await fetch('/delivery/set-choice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ needs_delivery: true })
        });

        const deliveryData = await deliveryResponse.json();
        console.log('✓ Delivery choice saved:', deliveryData);

        closeAddressModal();
        window.location.href = '{{ route("checkout.index") }}';

    } catch (error) {
        console.error('❌ Error saving:', error);
        window.location.href = '{{ route("checkout.index") }}';
    }
}

// Skip address for product page
function skipAddressModalForProduct() {
    console.log('⏭️ Skipping address input (Product Page)');
    
    fetch('/delivery/set-choice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ needs_delivery: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddressModal();
            window.location.href = '{{ route("checkout.index") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = '{{ route("checkout.index") }}';
    });
}

function closeAddressModal() {
    const addressModal = document.getElementById('addressModal');
    if (addressModal) {
        addressModal.style.display = 'none';
    }
}

function openAddressModal() {
    console.log('[DEBUG] openAddressModal meghívva!');
    const addressModal = document.getElementById('addressModal');
    if (addressModal) {
        addressModal.style.display = 'flex';
        console.log('[DEBUG] addressModal display beállítva flex-re');
    } else {
        console.log('[DEBUG] addressModal NEM található a DOM-ban!');
    }
}

</script>
@endpush

@push('scripts')
@if(isset($googleMapsApiKey) && $googleMapsApiKey)
<script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places,geometry"></script>
@else
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,geometry"></script>
@endif
@endpush
@endsection
