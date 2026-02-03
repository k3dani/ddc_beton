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
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal az építkezési cím megadásához -->
    <div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 40px; max-width: 700px; width: 90%; border-radius: 0; position: relative;">
            <h3 style="font-size: 28px; font-weight: 700; color: #004E2B; margin-bottom: 20px; font-family: 'Yantramanav', sans-serif;">Építkezési cím megadása</h3>
            <p style="font-size: 16px; margin-bottom: 30px; color: #666;">Kérjük, adja meg az építkezés címét. Ezt később a rendelés során fogjuk használni.</p>
            
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
                            id="save-address-btn"
                            onclick="handleAddressSave(event)"
                            style="flex: 1; padding: 15px 30px; background: #004E2B; color: white; border: 2px solid #004E2B; font-size: 18px; font-weight: 600; cursor: pointer; border-radius: 0; transition: all 0.3s;">
                        Tovább a termékválasztáshoz
                    </button>
                    <button type="button" 
                            onclick="skipAddressModal()"
                            style="padding: 15px 30px; background: white; color: #666; border: 2px solid #ddd; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 0; transition: all 0.3s;">
                        Kihagyom
                    </button>
                </div>
            </form>
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

/* Modal stílusok */
#addressModal {
    display: none;
}

#addressModal.show {
    display: flex !important;
}

#addressModal button:hover {
    opacity: 0.9;
}

#save-address-btn:disabled {
    background: #ccc !important;
    border-color: #ccc !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
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
    const lat = parseFloat(loc.latitude);
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
    
    // API kulcs ellenőrzése
    const apiKey = '{{ $googleMapsApiKey ?? "" }}';
    console.log('🔑 API Key length:', apiKey.length);
    
    if (!apiKey || apiKey.length < 10) {
        console.error('❌ Invalid API key:', apiKey);
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = '<div style="padding: 50px; text-align: center; color: red;">❌ Hiba: Google Maps API kulcs hiányzik!</div>';
        }
        return;
    }
    
    // Ellenőrizzük, hogy már be van-e töltve
    if (typeof google !== 'undefined' && google.maps) {
        console.log('✓ Google Maps already loaded');
        initializeMap();
        return;
    }
    
    // Google Maps script dinamikus betöltése a best practice szerint
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places,geometry&callback=initMapCallback&loading=async`;
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
            
            // Marker létrehozása
            const marker = new google.maps.Marker({
                map: map,
                position: { lat: lat, lng: lng },
                title: location.name,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 12,
                    fillColor: '#004E2B',
                    fillOpacity: 1,
                    strokeColor: 'white',
                    strokeWeight: 4,
                }
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
    console.log('📍 Location coordinates:', location.latitude, location.longitude);
    
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
    
    // Modal megnyitása a cím megadásához
    console.log('🔓 Opening address modal...');
    openAddressModal(location);
}

// Modal megnyitása
function openAddressModal(location) {
    console.log('🔓 Address modal opening for location:', location);
    const modal = document.getElementById('addressModal');
    if (modal) {
        modal.classList.add('show');
        console.log('✓ Modal opened');
    } else {
        console.error('❌ Modal element not found!');
    }
}

// Modal bezárása
function closeAddressModal() {
    const modal = document.getElementById('addressModal');
    modal.classList.remove('show');
    
    // Form reset
    document.getElementById('addressForm').reset();
    document.getElementById('construction-latitude').value = '';
    document.getElementById('construction-longitude').value = '';
    document.getElementById('distance-info').style.display = 'none';
    document.getElementById('distance-warning').style.display = 'none';
}

// Geocoding - cím koordinátákká alakítása
async function geocodeAddress(addressString) {
    console.log('🔍 Geocoding address:', addressString);
    
    // Ellenőrizzük, hogy a Google Maps API betöltődött-e
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

// Cím mentése - a gombról közvetlenül hívva
async function handleAddressSave(event) {
    console.log('🎯 SAVE ADDRESS BUTTON CLICKED!');
    event.preventDefault();
    event.stopPropagation();
    
    const postalCode = document.getElementById('postal-code').value.trim();
    const city = document.getElementById('city').value.trim();
    const streetName = document.getElementById('street-name').value.trim();
    const streetType = document.getElementById('street-type').value;
    const houseNumber = document.getElementById('house-number').value.trim();
    
    console.log('📝 Form values:', { postalCode, city, streetName, streetType, houseNumber });
    
    // Ha nincs cím megadva, csak lépjen tovább
    if (!postalCode && !city && !streetName) {
        console.log('⏭️ No address provided, skipping');
        skipAddressModal();
        return;
    }
    
    // Teljes cím összeállítása
    let fullAddress = '';
    if (postalCode) fullAddress += postalCode + ' ';
    if (city) fullAddress += city + ', ';
    if (streetName) fullAddress += streetName + ' ';
    if (streetType) fullAddress += streetType + ' ';
    if (houseNumber) fullAddress += houseNumber;
    
    fullAddress = fullAddress.trim();
    console.log('📍 Full address:', fullAddress);
    
    // Koordináták lekérése geocoding-gal
    const coords = await geocodeAddress(fullAddress);
    let lat = null;
    let lng = null;
    
    if (coords) {
        lat = coords.lat;
        lng = coords.lng;
        
        console.log('✓ Geocoded address:', fullAddress, 'to', lat, lng);
        console.log('📍 Selected location:', selectedLocation);
        
        // Távolság számítása
        if (selectedLocation && selectedLocation.latitude && selectedLocation.longitude) {
            console.log('📏 Calculating distance...');
            const distance = await calculateDistance(lat, lng, selectedLocation);
            
            if (distance) {
                console.log('📏 Calculated distance:', distance.toFixed(1), 'km');
                
                // Figyelmeztetés megjelenítése
                if (distance > 120) {
                    const warningText = 'Figyelem! Az építési cím több mint 120 km távolságra van a kiválasztott telephelytől (' + distance.toFixed(1) + ' km autóval). Ez különdíjat vonhat magával.';
                    
                    // Alert megjelenítése
                    alert(warningText);
                    console.warn('⚠️ Distance warning shown:', distance.toFixed(1), 'km');
                }
            } else {
                console.error('❌ Distance calculation returned null');
            }
        } else {
            console.error('❌ Selected location missing coordinates:', selectedLocation);
        }
    } else {
        console.error('❌ Geocoding failed for address:', fullAddress);
    }
    
    // AJAX kérések: ELŐSZÖR mentsük el a telephelyet, UTÁNA a címet
    console.log('💾 Saving location and address to session...');
    
    try {
        // 1. TELEPHELY MENTÉSE
        console.log('📍 Step 1: Saving selected location...');
        const locationResponse = await fetch(`/location/${selectedLocation.slug}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const locationData = await locationResponse.json();
        console.log('✓ Location saved:', locationData);
        
        // 2. CÍM MENTÉSE (ha van geocodolt cím)
        if (lat && lng && fullAddress) {
            console.log('🏗️ Step 2: Saving construction address...');
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
        } else {
            console.log('⏭️ No address to save, continuing with location only');
        }
        
        // Modal bezárása és átirányítás
        closeAddressModal();
        window.location.href = '{{ route("shop") }}';
        
    } catch (error) {
        console.error('❌ Error saving:', error);
        // Hiba esetén is menjünk tovább
        window.location.href = '{{ route("shop") }}';
    }
}

// Kihagyás - telephely választás mentése és továbblépés cím nélkül
function skipAddressModal() {
    console.log('⏭️ Skipping address input');
    
    // AJAX telephely mentés cím nélkül
    fetch(`/location/${selectedLocation.slug}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Átirányítás a shop-ba
            window.location.href = '{{ route("shop") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = '{{ route("shop") }}';
    });
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
            const distance = distanceInMeters / 1000; // km-re váltás
            const duration = element.duration.text; // pl. "2 óra 30 perc"
            
            console.log('✓ Road distance:', distance.toFixed(1), 'km');
            console.log('⏱️ Travel time:', duration);
            
            // Távolság megjelenítése
            document.getElementById('distance-value').textContent = distance.toFixed(1);
            document.getElementById('distance-info').style.display = 'block';
            
            // Figyelmeztetés ha több mint 120 km
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

console.log('✓ Map script loaded and ready');
</script>
@endpush
