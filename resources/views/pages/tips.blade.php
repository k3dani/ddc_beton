@extends('layouts.public')

@section('title', 'Tanácsok')

@section('content')
<div class="page page-tips">
    <div class="top-block">
        <div class="container-fluid">
            <h1>Tanácsok és útmutatók</h1>
            <p>Hasznos információk a beton használatához és építkezéshez</p>
        </div>
    </div>
    
    <div class="tips-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2>Beton megrendelési tanácsok</h2>
                    <ul>
                        <li>Pontosan mérje fel a szükséges mennyiséget</li>
                        <li>Vegye figyelembe a téli és nyári időjárási viszonyokat</li>
                        <li>Készítse elő az építési területet a szállítás előtt</li>
                        <li>Gondoskodjon megfelelő eszközökről és munkaerőről</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2>Tárolási és felhasználási tippek</h2>
                    <ul>
                        <li>A betont lehető leghamarabb használja fel</li>
                        <li>Gondoskodjon megfelelő utómunkálásról</li>
                        <li>Figyeljen a kötési időre</li>
                        <li>Védje a betont szélsőséges időjárástól</li>
                    </ul>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h2>Gyakran ismételt kérdések</h2>
                    <div class="faq">
                        <div class="faq-item">
                            <h3>Mennyi időre van szükség a beton kötéshez?</h3>
                            <p>A beton általában 24-48 óra alatt köt, de a teljes szilárdság eléréséhez 28 napra van szükség.</p>
                        </div>
                        <div class="faq-item">
                            <h3>Lehet-e télen is betonozni?</h3>
                            <p>Igen, de különleges óvintézkedések szükségesek. Használjon téli receptúrát és védje a betont a fagytól.</p>
                        </div>
                        <div class="faq-item">
                            <h3>Hogyan számítsam ki a szükséges mennyiséget?</h3>
                            <p>Szorozza meg a terület hosszát, szélességét és a kívánt vastagságot méterben. Az eredmény köbméterben lesz.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
