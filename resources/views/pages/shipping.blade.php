@extends('layouts.public')

@section('title', 'Szállítási feltételek')

@section('content')
<div class="page page-shipping">
    <div class="top-block">
        <div class="container-fluid">
            <h1>Szállítási feltételek</h1>
        </div>
    </div>
    
    <div class="shipping-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h2>Általános szállítási feltételek</h2>
                    <p>A Duna-Dráva Cement Kft. az alábbi feltételek mellett szállítja termékeit:</p>
                    
                    <h3>Szállítási terület</h3>
                    <p>Termékeinket Magyarország egész területére szállítjuk. A szállítási díjak a választott telephelytől és a szállítási címtől függnek.</p>
                    
                    <h3>Rendelés leadása</h3>
                    <p>A rendeléseket minimum 24 órával a kívánt szállítási időpont előtt kérjük leadni. Sürgős rendelések esetén vegye fel velünk a kapcsolatot telefonon.</p>
                    
                    <h3>Minimális rendelési mennyiség</h3>
                    <p>Beton termékek esetén a minimális rendelési mennyiség 0,5 m³. ECOBlock termékek esetén nincs minimális rendelési mennyiség.</p>
                    
                    <h3>Szállítási idő</h3>
                    <p>A szállítás időpontját előzetesen egyeztetjük. Kérjük, gondoskodjon arról, hogy a szállítási helyszínen megfelelő hozzáférés legyen biztosítva a betonkeverő vagy szállító jármű számára.</p>
                    
                    <h3>Különleges feltételek</h3>
                    <ul>
                        <li>A vevő köteles biztosítani a szállítójármű biztonságos közlekedését és manőverezését</li>
                        <li>A betonozás megkezdéséhez szükséges eszközök és munkaerő biztosítása a vevő feladata</li>
                        <li>Téli időszakban (0°C alatt) téli receptúrát ajánlunk, amely felárat tartalmaz</li>
                        <li>Várakozási idő az első 30 perc ingyenes, ezt követően percenként díjköteles</li>
                    </ul>
                    
                    <h3>Lemondás és módosítás</h3>
                    <p>A rendelés lemondása vagy módosítása a szállítási időpont előtt legalább 12 órával lehetséges. Későbbi lemondás esetén lemondási díjat számítunk fel.</p>
                    
                    <h3>Kapcsolat</h3>
                    <p>További kérdések esetén vegye fel velünk a kapcsolatot:</p>
                    <p>
                        <strong>Telefon:</strong> +36 27 511 080<br>
                        <strong>E-mail:</strong> info@dunaconcrete.hu
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
