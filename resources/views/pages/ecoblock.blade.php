@extends('layouts.public')

@section('title', 'ECOBlock')

@section('content')
<div class="page-ecoblock">
    <div class="top-block">
        <div class="wrapper">
            <div class="left">
                <div class="logo">
                    <img src="{{ asset('images/ecologo.svg') }}" alt="">
                </div>
                <div class="title">
                    <h1>Könnyebb és fenntarthatóbb építkezés</h1>
                </div>
                <div class="text">
                    <p>A betongyártók számára az egyik legfontosabb kérdés az, hogy mit kezdjenek a megmaradt betonnal, amely akkor keletkezik, ha a megrendelők nem használják fel az összes leszállított betont, és visszakerül a gyártóhoz, vagy egyszerűen akkor, ha a gyártás során megmarad a beton egy része.</p>
                    <p>Azt gondoljuk, hogy a megmaradt beton potenciális építőanyag.</p>
                </div>
                <div class="button">
                    <a class="btn" href="#" target="_self">Rendeljen most!</a>
                </div>
            </div>
            <div class="right">
                <div class="hero-image" style="background: url('/images/lego-siena.jpg.webp') center / cover no-repeat;">
                </div>
            </div>
        </div>
    </div>
    <div class="info-block">
        <div class="lego-block">
            <img src="{{ asset('images/legoblockhalf.svg') }}" alt="">
        </div>
        <div class="wrapper">
            <div class="left">
                <div class="subtitle">
                    <p>Mostantól nincs több kidobott beton</p>
                </div>
                <div class="title">
                    <h2>ECOBlock+ betontömbök</h2>
                </div>
                <div class="text">
                    <p>Sok betongyártó és -szállító vállalat a betontömbök gyártásához új betont használ. Mi újrahasznosítjuk a fel nem használt betont: öntőformák segítségével olyan betontömböket öntünk, amelyek alkalmasak az Ön építési munkáihoz.</p>
                </div>
                <div class="subtext">
                    <h2>A blokkok készítéséhez használt beton: C20/25</h2>
                </div>
            </div>
            <div class="right">
                <ul>
                    <li>Fenntartható megoldás a megmaradt beton kezelésére: lehetőség a hatékony felhasználásukra</li>
                    <li>Környezetbarát, mivel megmaradt betonból készül</li>
                    <li>Bontható és újra felhasználható az Ön egyéb projektjeihez</li>
                    <li>Megkönnyíti az építési munkálatokat: a tömbökből sokkal egyszerűbb és gyorsabb építkezni, és nincs szükség kötőanyagokra a szerkezetek összeállításához.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="info-block-image">
        <div class="wrapper">
            <div class="left">
                <div class="left-image" style="background: url('/images/bgimg1-scaled.jpg.webp') center center / cover no-repeat;">
                </div>
            </div>
            <div class="right">
                <div class="title">
                    <h2>Ideiglenes vagy állandó szerkezetekhez is ideális</h2>
                </div>
                <div class="list">
                    <ul>
                        <li>Válaszfalakhoz</li>
                        <li>Ideiglenes szerkezetekhez</li>
                        <li>Többcélú hangárokhoz</li>
                        <li>Teherhordó falakhoz</li>
                        <li>Ipari helyiségekhez</li>
                        <li>Zajvédő falakhoz</li>
                        <li>Tűzfalakhoz</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="boxes-block">
        <div class="wrapper">
            <div class="title">
                <h2>Az ECOBlock+ előnyei</h2>
            </div>
            <div class="boxes">
                <div class="box">
                    <div class="top">
                        <div class="title">
                            <h3>Alakpontosság</h3>
                        </div>
                        <div class="text">
                            <p>A betontömböket precíziós öntéssel készítjük, könnyen egymáshoz illeszthetők. A blokkok szabályos és pontos alakja megkönnyíti a védelmüket és tárolásukat.</p>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="icon" style="background: url('/images/legoblock1.svg') center / contain no-repeat;"></div>
                    </div>
                </div>
                <div class="box">
                    <div class="top">
                        <div class="title">
                            <h3>A szerkezet könnyen összeállítható</h3>
                        </div>
                        <div class="text">
                            <p>Az Ecoblock+ betontömbök formájuk miatt könnyen egymásra rakhatók és rögzíthetők előkészítve a szállításukat, és könnyen mozgathatók egyik helyről a másikra. A blokkok egymáshoz illesztve könnyen összeállíthatók, és az építés során nincs szükség kötőanyagra.</p>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="icon" style="background: url('/images/legoblock2.svg') center / contain no-repeat;"></div>
                    </div>
                </div>
                <div class="box">
                    <div class="top">
                        <div class="title">
                            <h3>Szerkezeti stabilitás</h3>
                        </div>
                        <div class="text">
                            <p>A betontömbök monolitikusak és tartósak, valamint a tetejükön lévő kiemelkedő részeknek és az aljukon lévő bemélyedéseknek köszönhetően szilárdan összekapcsolhatók egymással. Ez a stabilitás növeli a felhasználási lehetőségeket, és fokozza a biztonságot a munkaterületen.</p>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="icon" style="background: url('/images/legoblock3.svg') center / contain no-repeat;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="products-block">
        <div class="wrapper">
            <div class="title">
                <h2>A betonblokkok méretei</h2>
            </div>
            <div class="products">
                <ul>
                    <li>
                        <div class="left">
                            <div class="icon" style="background: url('{{ asset('images/ecoplus.svg') }}') center / contain no-repeat;"></div>
                        </div>
                        <div class="right">
                            <div class="name"><h3>1800 x 600 x 600</h3></div>
                            <a class="btn" href="#" target="_self">Vásárlás</a>
                        </div>
                    </li>
                    <li>
                        <div class="left">
                            <div class="icon" style="background: url('{{ asset('images/ecoplus.svg') }}') center / contain no-repeat;"></div>
                        </div>
                        <div class="right">
                            <div class="name"><h3>1200 x 600 x 600</h3></div>
                            <a class="btn" href="#" target="_self">Vásárlás</a>
                        </div>
                    </li>
                    <li>
                        <div class="left">
                            <div class="icon" style="background: url('{{ asset('images/ecoplus.svg') }}') center / contain no-repeat;"></div>
                        </div>
                        <div class="right">
                            <div class="name"><h3>600 x 600 x 600</h3></div>
                            <a class="btn" href="#" target="_self">Vásárlás</a>
                        </div>
                    </li>
                    <li>
                        <div class="left">
                            <div class="icon" style="background: url('{{ asset('images/ecoplus.svg') }}') center / contain no-repeat;"></div>
                        </div>
                        <div class="right">
                            <div class="name"><h3>1500 x 600 x 600</h3></div>
                            <a class="btn" href="#" target="_self">Vásárlás</a>
                        </div>
                    </li>
                    <li>
                        <div class="left">
                            <div class="icon" style="background: url('{{ asset('images/ecoplus.svg') }}') center / contain no-repeat;"></div>
                        </div>
                        <div class="right">
                            <div class="name"><h3>900 x 600 x 600</h3></div>
                            <a class="btn" href="#" target="_self">Vásárlás</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

