
let map, marker;
const pickupPoints = localizedData.pickupPoints; // Ensure this data exists.
const mapStyle = [
    {
        "featureType": "all",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "weight": "2.00"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#9c9c9c"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#eeeeee"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#7b7b7b"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#c8d7d4"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#070707"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    }
];

function initMap() {
    // Default location (Budapest, Hungary)
    const defaultLocation = { lat: 47.495314332917474, lng: 19.04096286943015 };
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 7,
        styles: mapStyle, // Apply the style array
        mapTypeControl: false, // Optional: remove map type control if you don't need it
        fullscreenControl: true, // Optional: allow fullscreen
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        const pos = marker.getPosition();
        calculateDistances(pos);
    });

    // Hide results initially
    document.getElementById('results').style.display = 'none';

    // Create the input element for the search box
    const input = document.createElement('input');
    input.id = "address-input";
    input.type = "text";
    input.placeholder = "Enter a location";
    input.style.cssText = "background-color: white; border: 1px solid #ccc; padding: 10px; margin-top: 10px; width: 300px;";

    // Add the search box input to the top center of the map
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

    // Initialize the autocomplete feature for the search box
    const autocomplete = new google.maps.places.Autocomplete(input);

    // When an address is selected, move the pin to that location
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();

        if (!place.geometry) {
            console.error("Autocomplete's returned place contains no geometry.");
            return;
        }

        // Move the marker to the selected location
        const location = place.geometry.location;
        marker.setPosition(location);
        map.setCenter(location);
        map.setZoom(14); // Zoom in on the selected location

        calculateDistances(location); // Calculate distances for the new location
    });
}

function calculateDistances(location) {
    const distances = pickupPoints.map(point => {
        const latLngA = new google.maps.LatLng(location.lat(), location.lng());
        const latLngB = new google.maps.LatLng(point.lat, point.lng);
        const distance = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB) / 1000; // in km
        return {
            ...point,
            distance
        };
    });

    distances.sort((a, b) => a.distance - b.distance);
    const nearestPoint = distances[0];
    const price = calculatePrice(nearestPoint.distance, nearestPoint.base_price, nearestPoint.price_increese__for_every_5_km);

    document.getElementById('nearest-point').innerText = nearestPoint.name;
    document.getElementById('distance').innerText = nearestPoint.distance.toFixed(2);
    document.getElementById('price').innerText = price.toFixed(2);
    document.getElementById('category-page').href = nearestPoint.link;

    // Store the data in local storage
    document.getElementById('category-page').addEventListener('click', function() {
        const selectedPumis = nearestPoint.pumis.map(pumi => ({
            name: pumi.name,
            fixed_price: pumi.fixed_price,
            price: pumi.price
        }));

        localStorage.setItem('pickupPointData', JSON.stringify({
            name: nearestPoint.name,
            distance: nearestPoint.distance,
            price: price,
            pumis: selectedPumis
        }));
    });



    // Show the selected coordinates
    document.getElementById('coordinates').innerText = `Lat: ${location.lat().toFixed(6)}, Lng: ${location.lng().toFixed(6)}`;

    // Display the results
    document.getElementById('results').style.display = 'block';
}

function calculatePrice(distance, basePrice, priceIncrease) {
    const additionalCost = Math.ceil(distance / 5) * parseFloat(priceIncrease);
    return parseFloat(basePrice) + additionalCost;
}

document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('map')) {
        initMap();
    } else {
        console.error("Map container not found.");
    }
});
