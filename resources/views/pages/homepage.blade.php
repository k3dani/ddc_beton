@extends('layouts.public')

@section('title', 'Homepage')

@section('content')
<div class="page page-home">
    @if(session('error'))
        <div class="alert alert-warning" style="margin: 20px auto; max-width: 1200px; padding: 15px 20px; background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; border-radius: 4px;">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="top-block" style="background: url('{{ asset('images/home-hero.jpg') }}') center / cover no-repeat;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h1>A legkényelmesebb módja annak, hogy betont rendeljen közvetlen kiszállítással</h1>
                    <p class="arrow-anchor"></p>
                </div>
                <div class="col-md-6 align-center">
                    <h2 class="sub-heading">Nem kell várni a visszahívásra vagy az értékesítő válaszára</h2>
                    <a href="#first-step" class="btn smooth">Rendelés indítása</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="about-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h3>01 / Egyszerűség</h3>
                    <ul>
                        <li>
                            <p>Javasoljuk az Ön igényeinek megfelelő betontípust. Nézze meg a beton árát, és rendeljen online az Önnek megfelelő szállítási időpontot kiválasztva.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3>02 / Garantált minőség</h3>
                    <ul>
                        <li>
                            <p>Szállításkor minden termékünket minőség-ellenőrzésnek vetjük alá. A globálisan jelen levő HeidelbergMaterials vállalatcsoport része vagyunk, amely elkötelezett a magas minőség és az átlátható működési normák mellett.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3>03 / Fenntarthatóság</h3>
                    <ul>
                        <li>
                            <p>Fontos számunkra a környezeti fenntarthatóság, ezért termékportfólónkban alacsonyabb környezeti lábnyomú betontermékeket (evoBuild) is kínálunk.</p>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div id="first-step" class="location-block" style="padding-bottom: 80px;">
        <div class="wrapper">
            <div class="heading">
                <h2>Válassza ki az Önhöz legközelebb eső telephelyet</h2>
                <p>Kérjük, kattintson a térképen arra a telephelyre, amelyik Önhöz a legközelebb található.</p>
            </div>
            <div class="map-wrap">
                <div id="map" style="width: 100%; height: 600px; border: 1px solid #ddd; background: #e5e5e5; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #666;">
                    <div id="map-loading">🗺️ Térkép betöltése...</div>
                </div>
                <div id="location-info" style="display:none; margin-top: 30px; margin-bottom: 50px; padding: 30px; background: #f8f9fa; border: 2px solid #004E2B; border-radius: 0;">
                    <h4 style="font-size: 24px; font-weight: 700; color: #004E2B; margin-bottom: 20px; font-family: 'Yantramanav', sans-serif;">Kiválasztott telephely</h4>
                    <p style="font-size: 18px; margin-bottom: 25px; color: #333;"><strong style="color: #004E2B;">Telephely:</strong> <span id="selected-location-name"></span></p>
                    <button id="continue-btn" class="btn" style="display: inline-block; text-decoration: none; font-size: 20px; color: #fff; background: #004E2B; padding: 20px 65px; border: 2px solid #004E2B; border-radius: 0; font-weight: 500; font-family: 'Yantramanav', sans-serif; transition: all 0.3s ease; text-align: center; cursor: pointer;">Tovább a termékválasztáshoz</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Select mezők stílusai */
#county-select:hover:not([disabled]),
#city-select:hover:not([disabled]) {
    border-color: #004E2B !important;
}

#county-select:focus,
#city-select:focus {
    outline: none !important;
    border-color: #004E2B !important;
    box-shadow: 0 0 0 3px rgba(0, 78, 43, 0.1) !important;
}

#county-select:disabled,
#city-select:disabled {
    background: #f5f5f5 !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

/* Gomb hover effekt */
#continue-btn:hover {
    background: #fff !important;
    color: #004E2B !important;
    border-color: #004E2B !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 78, 43, 0.2);
}

/* Információs doboz animáció */
#location-info {
    animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
console.log('🚀 Starting map initialization script...');

// Globális változók
let map;
let markers = [];
let selectedLocation = null;

// Telephelyek adatai a szerverről
const locations = @json($locations);
console.log('📍 Locations loaded:', locations);
console.log('📍 Number of locations:', locations.length);

// Ellenőrzés, hogy vannak-e telephelyek érvényes koordinátákkal
const validLocations = locations.filter(loc => {
    const lat =Az admin felületeken maradt egy információs üzenet, hogy a koordináták automatikusan kitöltődnek/frissülnek mentéskor. parseFloat(loc.latitude);
    const lng = parseFloat(loc.longitude);
    const valid = !isNaN(lat) && !isNaN(lng);
    if (!valid) {
        console.warn(`⚠️ Invalid location: ${loc.name}`, loc);
    }
    return valid;
});
console.log('📍 Valid locations with coordinates:', validLocations.length);

// Global callback for Google Maps
window.initMapCallback = function() {
    console.log('✓ Google Maps API loaded via callback');
    initializeMap();
};

// Google Maps API betöltő függvény
function loadGoogleMaps() {
    console.log('📍 Loading Google Maps API...');
    
    // Ellenőrizzük, hogy már be van-e töltve
    if (typeof google !== 'undefined' && google.maps) {
        console.log('✓ Google Maps already loaded');
        initializeMap();
        return;
    }
    
    // Google Maps script dinamikus betöltése a best practice szerint
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey ?? "" }}&libraries=places,geometry,marker&callback=initMapCallback&loading=async';
    script.async = true;
    script.defer = true;
    
    script.onerror = function() {
        console.error('❌ Failed to load Google Maps API');
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = '<div style="padding: 50px; text-align: center; color: red;">Hiba: A Google Maps API nem töltődött be. Kérjük, frissítse az oldalt!</div>';
        }
    };
    
    document.head.appendChild(script);
}

// Azonnal indítjuk a betöltést
loadGoogleMaps();

// Térkép inicializálása
function initializeMap() {
    console.log('🗺️ Initializing map...');
    
    if (locations.length === 0) {
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = '<div style="padding: 50px; text-align: center; color: orange; font-family: Arial;">⚠️ Nincsenek elérhető telephelyek az adatbázisban!</div>';
        }
        return;
    }
    
    // Ellenőrizzük, hogy létezik-e a map elem
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('❌ Map element not found!');
        return;
    }
    
    console.log('✓ Map element found');
    
    // Ellenőrizzük, hogy a Google Maps API betöltődött-e
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error('❌ Google Maps API not loaded!');
        mapElement.innerHTML = '<div style="padding: 50px; text-align: center; color: red; font-family: Arial;">❌ Google Maps API nem töltődött be!</div>';
        return;
    }
    
    console.log('✓ Google Maps API available');
    
    // Elrejtjük a loading szöveget
    const loadingDiv = document.getElementById('map-loading');
    if (loadingDiv) {
        loadingDiv.style.display = 'none';
    }
    
    // Magyarország középpontja
    const hungaryCenter = { lat: 47.1625, lng: 19.5033 };
    
    try {
        map = new google.maps.Map(mapElement, {
            zoom: 7,
            center: hungaryCenter,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            mapId: 'BETONPLUSS_MAP'
        });
        
        console.log('✓ Map created successfully');
        
        // Telephelyek marker-ei
        locations.forEach(location => {
            // Ellenőrizzük, hogy vannak-e érvényes koordináták
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            if (isNaN(lat) || isNaN(lng)) {
                console.warn(`⚠️ Invalid coordinates for location: ${location.name}`, location);
                return; // Skip this location
            }
            
            // Marker elem létrehozása
            const markerElement = document.createElement('div');
            markerElement.style.cssText = `
                width: 40px;
                height: 40px;
                background-color: #004E2B;
                border: 4px solid white;
                border-radius: 50%;
                box-shadow: 0 3px 8px rgba(0,0,0,0.4);
                cursor: pointer;
                transition: transform 0.2s;
            `;
            
            markerElement.onmouseover = function() {
                this.style.transform = 'scale(1.2)';
            };
            markerElement.onmouseout = function() {
                this.style.transform = 'scale(1)';
            };
            
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: { lat: lat, lng: lng },
                title: location.name,
                content: markerElement
            });
            
            // InfoWindow a telephely nevével
            const infoWindow = new google.maps.InfoWindow({
                content: `<div style="padding: 10px; font-family: 'Yantramanav', sans-serif;">
                    <strong style="font-size: 16px; color: #004E2B;">${location.name}</strong><br>
                    <small style="color: #666;">Kattintson a telephely kiválasztásához</small>
                </div>`
            });
            
            // Marker click esemény - telephely kiválasztása
            marker.addListener('click', () => {
                selectLocation(location, marker, infoWindow);
            });
            
            markers.push({ marker, location, infoWindow });
        });
        
        console.log('✓ Markers added:', markers.length);
        
        // "Tovább" gomb esemény
        const continueBtn = document.getElementById('continue-btn');
        if (continueBtn) {
            continueBtn.addEventListener('click', () => {
                if (selectedLocation) {
                    window.location.href = `/location/${selectedLocation.slug}`;
                }
            });
        }
        
    } catch (error) {
        console.error('❌ Error initializing map:', error);
        mapElement.innerHTML = '<div style="padding: 50px; text-align: center; color: red;">Hiba a térkép betöltésénél: ' + error.message + '</div>';
    }
}

// Telephely kiválasztása
function selectLocation(location, marker, infoWindow) {
    console.log('📍 Location selected:', location.name);
    
    selectedLocation = location;
    
    // InfoWindow megjelenítése
    // Előbb bezárjuk az összes többi InfoWindow-t
    markers.forEach(m => {
        if (m.infoWindow) {
            m.infoWindow.close();
        }
    });
    infoWindow.open(map, marker);
    
    // Térkép központosítása a kiválasztott telephelyre
    const lat = parseFloat(location.latitude);
    const lng = parseFloat(location.longitude);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(12);
    }
    
    // Location info megjelenítése
    document.getElementById('selected-location-name').textContent = location.name;
    document.getElementById('location-info').style.display = 'block';
    
    // Smooth scroll az info box-hoz
    document.getElementById('location-info').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

console.log('✓ Map script loaded and ready');
</script>
@endpush
