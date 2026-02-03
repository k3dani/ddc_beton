@extends('layouts.public')

@section('content')
<div class="page page-shop">
    <div class="top-block"
        style="background: url('/images/beton-epulet3.jpg') center / cover no-repeat;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h1>Beton az Ön szükségleteihez szabva</h1>
                    <h3 class="sub-heading">
                    </h3>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px auto; max-width: 1200px; padding: 15px 20px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if($selectedLocation)
        <div style="background: #e8f5e9; padding: 20px 0; margin-bottom: 30px;">
            <div class="container-fluid">
                <div style="display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div>
                        <div style="color: #2e7d32; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            Kiválasztott telephely
                        </div>
                        <div style="color: #1b5e20; font-size: 20px; font-weight: 700;">
                            {{ $selectedLocation->name }}
                        </div>
                    </div>
                    <a href="{{ route('homepage') }}" 
                       style="color: #2e7d32; text-decoration: none; font-size: 14px; font-weight: 600; border: 2px solid #2e7d32; padding: 8px 20px; border-radius: 4px; transition: all 0.3s;">
                        Telephely módosítása
                    </a>
                </div>
            </div>
        </div>
    @endif
    
    <div id="house" class="house-block">
        <div class="container-fluid">
            <div class="heading">
                <h2>Beton a felhasználás célja szerint</h2>
                <p>Keresse meg az Önnek legmegfelelőbb betontípust aszerint, hogy milyen célra szeretné felhasználni</p>
            </div>
            <div class="house">
                <img src="/images/greenhousecar-2-1.svg" alt="">

                @foreach($categoriesOnHouse as $houseCategory)
                    @php
                        $hasProducts = $houseCategory->products->count() > 0;
                        $disabledClass = !$hasProducts ? 'disabled' : '';
                    @endphp
                    
                    <div class="info-box {{ $disabledClass }}" style="left: {{ $houseCategory->position_x }}; top: {{ $houseCategory->position_y }};">
                        <div class="content">
                            <div class="bottom">
                                <p>{{ $houseCategory->name }}</p>
                                <img class="arrow"
                                     src="{{ asset('images/arrow-down.svg') }}"
                                     alt="">
                            </div>
                            <div class="sub-content">
                                @if($hasProducts)
                                    @foreach($houseCategory->products as $product)
                                        @php
                                            $locationPrice = $selectedLocation ? $product->locations->first() : null;
                                            $grossPrice = $locationPrice ? $locationPrice->pivot->gross_price : null;
                                        @endphp
                                        <div class="box">
                                            <a href="{{ route('shop.product', $product->slug) }}">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    </svg>
                                                @endif
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600;">{{ $product->name }}</div>
                                                    @if($grossPrice)
                                                        <div style="color: #666; font-size: 14px;">{{ number_format($grossPrice, 0, ',', ' ') }} Ft</div>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="box" style="padding: 15px; text-align: center; color: #999;">
                                        Jelenleg nincs elérhető termék ebben a kategóriában.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="expander">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19.245" height="19.245"
                                 viewBox="0 0 19.245 19.245">
                                <path id="Path_48" data-name="Path 48"
                                      d="M2703.236,572.927l-5.377-.009,0-2.848,5.377.009.009-5.378,2.849,0-.009,5.378,5.376.009,0,2.848-5.376-.009-.009,5.378-2.849,0Z"
                                      transform="translate(-2306.978 1517.992) rotate(-45)" fill="#f9f9f9"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="product-block">
        <div class="container-fluid">
            @if(!$selectedLocation)
                <div class="alert alert-info mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    <strong>Figyelem:</strong> Kérjük, válasszon telephelyet a főoldalon, hogy láthassa a termékeket és árakat!
                </div>
            @endif

            <!-- Kategória szűrő -->
            @if($selectedLocation && $allCategories->count() > 0)
                <div style="margin-bottom: 40px; text-align: center;">
                    <div style="display: inline-flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
                        <a href="{{ route('shop') }}" 
                           class="category-filter {{ !isset($category) ? 'active' : '' }}"
                           style="padding: 12px 30px; border: 2px solid #004E2B; color: {{ !isset($category) ? '#fff' : '#004E2B' }}; background: {{ !isset($category) ? '#004E2B' : 'transparent' }}; text-decoration: none; font-weight: 600; transition: all 0.3s; border-radius: 4px; display: inline-block;">
                            Összes termék
                        </a>
                        @foreach($allCategories as $cat)
                            <a href="{{ route('shop.category', $cat->slug) }}" 
                               class="category-filter {{ isset($category) && $category->id === $cat->id ? 'active' : '' }}"
                               style="padding: 12px 30px; border: 2px solid #004E2B; color: {{ isset($category) && $category->id === $cat->id ? '#fff' : '#004E2B' }}; background: {{ isset($category) && $category->id === $cat->id ? '#004E2B' : 'transparent' }}; text-decoration: none; font-weight: 600; transition: all 0.3s; border-radius: 4px; display: inline-block;">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div id="filter-content" class="row">
                @forelse($products as $product)
                    @php
                        // Az árakat a kiválasztott telephelyhez kérjük le
                        $locationPrice = $selectedLocation ? $product->locations->first() : null;
                        $grossPrice = $locationPrice ? $locationPrice->pivot->gross_price : null;
                        $netPrice = $locationPrice ? $locationPrice->pivot->net_price : null;
                    @endphp
                    
                    <div class="col-xl-4 col-lg-4 col-md-6 single product">
                        <a href="{{ route('shop.product', $product->slug) }}">
                            <div class="top">
                                <div class="image" style="height: 250px; overflow: hidden; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;" />
                                    @else
                                        <img src="/images/betonas-grindims.svg" alt="{{ $product->name }}" />
                                    @endif
                                </div>
                                <h3>{{ $product->name }}</h3>
                                @if($product->technical_name)
                                    <p style="color: #888; font-size: 14px; margin-top: 5px;">{{ $product->technical_name }}</p>
                                @endif
                                
                                @if($grossPrice && $netPrice)
                                    <div class="meta">
                                        <div class="meta-single">
                                            <span>
                                                Bruttó: {{ number_format($grossPrice, 0, ',', ' ') }} Ft<br>
                                                <span style="color: #888; font-size: 14px;">Nettó: {{ number_format($netPrice, 0, ',', ' ') }} Ft</span>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="bottom">
                                <div class="btn">
                                    <span>Kosárba</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded text-center">
                            @if($selectedLocation)
                                Jelenleg nincs elérhető termék ezen a telephelyen.
                            @else
                                Kérjük, válasszon telephelyet a termékek megtekintéséhez.
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
