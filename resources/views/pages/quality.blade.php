@extends('layouts.public')

@section('title', 'Minőség')

@section('content')
<div class="page page-quality">
    <div class="top-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h1>Minőség</h1>
                    <p>A DDC csapata a legjobb gyakorlatokat alkalmazza termékei minőségének biztosítására, de a minőség az ügyfelek együttműködésén is múlik:</p>
                    <ul>
                        <li>
                            <span>01</span>
                            <p>A tervezett szállítási időpontban álljon készen a beton ürítésére és azonnali bedolgozására – ha az ajánlottnál hosszabb ideig áll, a beton megszilárdul és megváltoznak a betontechnológiai tulajdonságai.</p>
                        </li>
                        <li>
                            <span>02</span>
                            <p>Nem javasoljuk, hogy a betonhoz vizet vagy más adalékszereket adjon - ha kétségei vannak a beton bedolgozhatóságával kapcsolatban, járművezetőink ezt a szállításkor ingyenesen ellenőrzik.</p>
                        </li>
                        <li>
                            <span>03</span>
                            <p>A beton zökkenőmentes és gyors ürítéséhez fontos, hogy a helyszín megfelelően megközelíthető legyen.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="photo pad-120" style="background: url('/images/ddc-betonmixer.jpg') center / cover no-repeat;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="assurance-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <h2>Minőségbiztosítás</h2>
                    <div class="text">
                        <p>A transzport beton gyártását egy akkreditált tanúsító cég, a CEMKUT Kft. tanúsítja;</p>
                        <p>A gyártott beton minőségét bevizsgáljuk a HC Concrete minden részlegében megtalálható laboratóriumokban;</p>
                        <p>A minőségbiztosítási rendszert a Beton Technológia Centrum Kft-vel együttműködésben dolgoztuk ki.</p>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <h3>A minőségbiztosítás alapja az MSZ4798:2016 és MSZ EN 13670 szabványok</h3>
                    <ul>
                        <li>
                            <p>Az előre kevert beton gyártásához tanúsított, akkreditált laboratóriumokban ellenőrzött receptúrákat használunk;</p>
                        </li>
                        <li>
                            <p>A gyártáshoz minőségi tanúsítvánnyal [teljesítménynyilatkozat] rendelkező nyersanyagokat használunk;</p>
                        </li>
                        <li>
                            <p>Téli időszakban a beton hőmérséklete nem csökken 10 °C alá;</p>
                        </li>
                        <li>
                            <p>A minőségtanúsítványokat és a megfelelőségi nyilatkozatot ügyfeleink rendelkezésére bocsátjuk.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="partner-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2>Legyen partnerünk</h2>
                    <p>Ön tapasztalt kivitelező szakember?<br />Ismerje meg, milyen előnyöket kínálunk partnereinknek!</p>
                </div>
                <div class="col-md-6 align-center">
                    <a class="btn" href="#" target="_self">Regisztrálok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
