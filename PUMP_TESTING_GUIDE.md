# Pumpa Szolgáltatás Tesztelési Útmutató

## Implementáció Áttekintés

A pumpa szolgáltatás teljes mértékben integrálva van a checkout flow-ba. A felhasználók most választhatnak:
- Csak terméket (sem fuvar, sem pumpa)
- Terméket + fuvart
- Terméket + pumpát
- Terméket + fuvart + pumpát

## Adatbázis Struktúra

### Orders tábla - új mezők:
- `pump_id` (foreign key, nullable) - Kiválasztott pumpa ID
- `pump_type` (string, nullable) - Pumpa típusa (pl. "Mobilpumpa 32m")
- `pump_fixed_fee` (decimal, nullable) - Fix díj Ft-ban
- `pump_hourly_fee` (decimal, nullable) - Óradíj Ft-ban

### Pumps tábla (meglévő):
- `id` - Pumpa ID
- `location_id` - Telephely ID (foreign key)
- `type` - Pumpa típusa
- `boom_length` - Gémhossz méterben
- `fixed_fee` - Fix díj
- `hourly_fee` - Óradíj
- `created_at`, `updated_at` - Időbélyegek

## Tesztadatok

### Location 1 (Vác) pumpái:
- ID 5: Mobilpumpa 32m - 32m gémhossz - 45,000 Ft fix - 12,000 Ft/óra
- ID 6: Mobilpumpa 24m - 24m gémhossz - 38,000 Ft fix - 10,000 Ft/óra
- ID 7: Mobilpumpa 42m - 42m gémhossz - 55,000 Ft fix - 15,000 Ft/óra

### Location 2 (Budapest) pumpái:
- ID 3: IFA - 40m gémhossz - 20,000 Ft fix - 10,000 Ft/óra
- ID 4: ZIL - 20m gémhossz - 26,000 Ft fix - 14,000 Ft/óra

## API Végpontok

### GET /pump/get-available
**Session requirement:** `selected_location_id` kell, hogy beállítva legyen

**Válasz:**
```json
{
  "success": true,
  "pumps": [
    {
      "id": 6,
      "type": "Mobilpumpa 24m",
      "boom_length": 24,
      "fixed_fee": 38000,
      "hourly_fee": 10000
    },
    {
      "id": 5,
      "type": "Mobilpumpa 32m",
      "boom_length": 32,
      "fixed_fee": 45000,
      "hourly_fee": 12000
    },
    {
      "id": 7,
      "type": "Mobilpumpa 42m",
      "boom_length": 42,
      "fixed_fee": 55000,
      "hourly_fee": 15000
    }
  ]
}
```

**Megjegyzés:** A pumpák gémhossz szerint rendezve (ASC) érkeznek.

### POST /pump/set-choice
**Request body:**
```json
{
  "pump_id": 5
}
```

**Session storage:**
- `pump_id` - Kiválasztott pumpa ID
- `pump_type` - Pumpa típusa
- `pump_boom_length` - Gémhossz
- `pump_fixed_fee` - Fix díj
- `pump_hourly_fee` - Óradíj

**Válasz:**
```json
{
  "success": true,
  "message": "Pumpa sikeresen kiválasztva!"
}
```

## Tesztelési Lépések

### 1. Admin Felület - Pumpa Kezelés

1. **Bejelentkezés admin-ként:**
   - URL: http://localhost:8000/admin/login
   - Telephely szerkesztése: http://localhost:8000/admin/locations/1/edit

2. **Pumpa hozzáadása:**
   - Kattints a "Pumpa hozzáadása" gombra
   - Töltsd ki az adatokat:
     - Típus: pl. "Mobilpumpa 50m"
     - Gémhossz: pl. 50
     - Fix díj: pl. 60000
     - Óradíj: pl. 16000
   - Kattints "Mentés"
   - Ellenőrizd: Az új pumpa megjelenik a táblázatban, gémhossz szerint rendezve

3. **Pumpa szerkesztése:**
   - Kattints a "Szerkesztés" gombra egy pumpa mellett
   - Módosítsd az adatokat
   - Kattints "Mentés"
   - Ellenőrizd: A módosított adatok megjelennek

4. **Pumpa törlése:**
   - Kattints a "Törlés" gombra
   - Erősítsd meg a törlést
   - Ellenőrizd: A pumpa eltűnik a listából

### 2. Publikus Oldal - Checkout Flow

1. **Termék hozzáadása a kosárhoz:**
   - URL: http://localhost:8000/shop
   - Válassz egy telephelyet (pl. Vác)
   - Adj hozzá terméket a kosárhoz
   - Menj a kosárhoz: http://localhost:8000/cart

2. **Csak pumpa szolgáltatás (fuvar nélkül):**
   - Kattints "Megrendelés véglegesítése"
   - A modál megnyílik
   - **NE jelöld be** a "Kérek fuvar szolgáltatást" négyzetet
   - **Jelöld be** a "Kérek pumpa szolgáltatást" négyzetet
   - A pumpa dropdown megjelenik
   - Válassz egy pumpát (pl. Mobilpumpa 32m)
   - Ellenőrizd: A pumpa részletei megjelennek (gémhossz, fix díj, óradíj)
   - Kattints "Tovább a rendeléshez"

3. **Checkout oldal ellenőrzése:**
   - URL: http://localhost:8000/checkout
   - Ellenőrizd a sárga boxot: "✓ Pumpa szolgáltatás igényelve"
   - Látható-e:
     - Pumpa típus
     - Gémhossz
     - Fix díj
     - Óradíj megjegyzés
   - Ellenőrizd az összesítőt:
     - Részösszeg (termékek)
     - Pumpa fix díj (külön sor)
     - Irányár (részösszeg + pumpa fix díj)

4. **Fuvar + Pumpa szolgáltatás:**
   - Menj vissza a kosárhoz
   - Kattints "Megrendelés véglegesítése"
   - **Jelöld be** mindkét négyzetet:
     - "Kérek fuvar szolgáltatást"
     - "Kérek pumpa szolgáltatást"
   - Ellenőrizd: Mindkét szolgáltatás űrlapja megjelenik
   - Töltsd ki a fuvar adatokat (építési cím térképen)
   - Válassz pumpát
   - Kattints "Tovább a rendeléshez"
   - Checkout oldalon ellenőrizd:
     - Zöld box: fuvar szolgáltatás
     - Sárga box: pumpa szolgáltatás
     - Összesítő: részösszeg + fuvardíj + pumpa fix díj

5. **Rendelés véglegesítése:**
   - Töltsd ki a vásárlói adatokat
   - Kattints "Rendelés elküldése"
   - Ellenőrizd: A rendelés sikeresen létrejött

### 3. Adatbázis Ellenőrzés

A rendelés után ellenőrizd az orders táblát:

```bash
cd /var/www/betonpluss/betoonpluss/laravel
php artisan tinker --execute="
use App\Models\Order;
\$order = Order::latest()->first();
echo 'Order ID: ' . \$order->id . PHP_EOL;
echo 'Pump ID: ' . \$order->pump_id . PHP_EOL;
echo 'Pump Type: ' . \$order->pump_type . PHP_EOL;
echo 'Pump Fixed Fee: ' . \$order->pump_fixed_fee . PHP_EOL;
echo 'Pump Hourly Fee: ' . \$order->pump_hourly_fee . PHP_EOL;
echo 'Total Amount: ' . \$order->total_amount . PHP_EOL;
"
```

## Edge Case Tesztek

### 1. Pumpa választás nélküli submission:
- Jelöld be a "Kérek pumpa szolgáltatást" négyzetet
- **NE** válassz pumpát a dropdown-ból
- Próbálj továbblépni
- **Elvárt eredmény:** Hibaüzenet: "Kérjük, válasszon pumpát!"

### 2. Telephely váltás pumpa választás után:
- Válassz pumpát
- Válts telephelyet a shop oldalon
- **Elvárt eredmény:** A kosár törlődik (telephely váltásnál)

### 3. Location pumpák nélkül:
- Válassz olyan telephelyet, ahol nincs pumpa (pl. Győr, Location 3)
- Jelöld be a pumpa négyzetet
- **Elvárt eredmény:** "Ezen a telephelyen nincs elérhető pumpa."

## Komponensek és Fájlok

### Backend:
- **Controller:** `/app/Http/Controllers/CheckoutController.php`
  - `getAvailablePumps()` - Pumpa lista lekérése
  - `setPumpChoice()` - Pumpa választás mentése
  - `index()` - Checkout oldal (pumpa változók átadása)
  - `store()` - Rendelés mentése (pumpa adatokkal)

- **Migration:** `/database/migrations/2026_01_29_114531_add_pump_fields_to_orders_table.php`
- **Routes:** `/routes/web.php`
  - GET `/pump/get-available`
  - POST `/pump/set-choice`

### Frontend:
- **Cart View:** `/resources/views/pages/cart.blade.php`
  - Delivery Modal HTML (checkbox UI)
  - JavaScript event handlers
  - AJAX calls (loadAvailablePumps, proceedToCheckout)

- **Checkout View:** `/resources/views/pages/checkout.blade.php`
  - Pumpa info box (sárga színnel)
  - Összesítő tábla pumpa fix díj sorral

## Session Változók

### Pumpa választás:
- `pump_id` - int
- `pump_type` - string
- `pump_boom_length` - float
- `pump_fixed_fee` - float
- `pump_hourly_fee` - float

### Fuvar választás (meglévő):
- `needs_delivery` - boolean
- `construction_address` - string
- `construction_distance_km` - float
- `delivery_volume_cbm` - float
- `delivery_price_calculated` - float

## Számítások

### Végösszeg kalkuláció (CheckoutController@index):
```php
$total = $productsTotal;
if ($pumpFixedFee) {
    $total += $pumpFixedFee;
}
// Note: deliveryPrice is calculated but not added to total automatically
```

### Rendelés összeg (CheckoutController@store):
```php
$totalAmount = $productsTotal;
if ($deliveryPrice) {
    $totalAmount += $deliveryPrice;
}
if ($pumpFixedFee) {
    $totalAmount += $pumpFixedFee;
}
```

## Troubleshooting

### Probléma: A pumpák nem jelennek meg
- Ellenőrizd: `session('selected_location_id')` be van-e állítva
- Ellenőrizd: A location-nek vannak-e pumpái
- Ellenőrizd a browser console-t hibákért

### Probléma: A pumpa fix díj nem jelenik meg az összegben
- Ellenőrizd: `$pumpFixedFee` át lett-e adva a view-nak
- Ellenőrizd: A blade `@if($pumpId)` feltétel teljesül-e

### Probléma: A modal nem nyílik meg
- Ellenőrizd: A `@push('scripts')` blokk betöltődött-e
- Ellenőrizd: A `checkoutButton` ID egyezik-e
- Ellenőrizd a browser console-t

### Probléma: AJAX hívás 419 (CSRF token mismatch)
- Ellenőrizd: A `@csrf` token jelen van-e a page-en
- Ellenőrizd: A `document.querySelector('meta[name="csrf-token"]')` létezik-e

## Jövőbeli Fejlesztések

- [ ] Pumpa óradíj kalkulátor (becsült munkaidő alapján)
- [ ] Pumpa foglaltsági naptár
- [ ] Email értesítés pumpa igényléséről
- [ ] Admin dashboard pumpa foglalások kezelésére
- [ ] Pumpa képek/specifikációk megjelenítése
- [ ] Pumpa elérhetőség valós idejű ellenőrzése

## Kapcsolódó Dokumentumok

- LOCATION_PRODUCT_PRICE_README.md - Telephely-specifikus árak
- MIGRATION_README.md - Migrációk dokumentációja
