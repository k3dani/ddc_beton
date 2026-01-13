# Telephely-Termék-Ár Mátrix Rendszer

## Áttekintés

Ez a rendszer lehetővé teszi, hogy minden telephelyen (Budapest, Székesfehérvár stb.) külön árakat állíts be minden termékhez.

## Adatbázis struktúra

### Táblák

1. **locations** - Telephelyek
   - id, name, slug, latitude, longitude, is_active

2. **products** - Termékek
   - id, name, slug, code (pl: C30/37-XF3-16-F2), description, image, is_active

3. **location_product** - Telephely-Termék kapcsolótábla árral
   - location_id, product_id, gross_price, net_price, is_available

## Admin felület használata

### Árak beállítása

1. Navigálj az Admin Dashboard-ra: `/admin/dashboard`
2. Kattints a "Telephely-Termék-Ár mátrix" menüpontra
3. Látni fogsz egy táblázatot:
   - Sorok: Termékek
   - Oszlopok: Telephelyek (mindegyikhez bruttó és nettó ár)
4. Töltsd ki az árakat:
   - Ha egy termék nem elérhető egy telephelyen, hagyd üresen
   - Ha van ár, töltsd ki mind a bruttó, mind a nettó mezőt
5. Kattints a "Árak mentése" gombra

### URL: `/admin/location-product-prices`

## Publikus oldal működése

### 1. Felhasználó választ telephelyet a főoldalon:
   - Megye kiválasztása
   - Város kiválasztása
   - "Tovább a rendeléshez" gombra kattintás

### 2. A rendszer:
   - Session-be menti a kiválasztott telephely ID-ját
   - Átirányít a shop oldalra

### 3. Shop oldalon:
   - Csak azok a termékek jelennek meg, amelyekhez van ár az adott telephelyre
   - Az árak dinamikusan jelennek meg az adatbázisból
   - Ha nincs telephely kiválasztva, figyelmeztetés jelenik meg

## Telephelyek hozzáadása

```bash
php artisan db:seed --class=LocationSeeder
```

Vagy manuálisan az admin felületen: `/admin/locations`

## Termékek hozzáadása

Admin felület: `/admin/products`

1. Kattints "Új termék"
2. Töltsd ki:
   - Név
   - Slug (automatikusan generálódik)
   - Kód (pl: C20/25-XC1-16-F3)
   - Leírás
   - Kép
3. Mentés után az "Árak" menüpontban állítsd be az árakat minden telephelyre

## Példa munkafolyamat

1. **Admin**: Létrehoz 2 telephelyet (Budapest, Székesfehérvár)
2. **Admin**: Létrehoz 5 terméket
3. **Admin**: Beállítja az árakat minden telephely-termék kombinációra
4. **Felhasználó**: Kiválasztja Budapest-et a főoldalon
5. **Felhasználó**: Shop oldalon látja a budapesti árakat
6. **Felhasználó**: Visszamegy a főoldalra, kiválasztja Székesfehérvárt
7. **Felhasználó**: Shop oldalon most székesfehérvári árakat lát

## Route-ok

### Publikus:
- `GET /` - Homepage (telephelyválasztás)
- `GET /location/{slug}` - Telephely kiválasztása
- `GET /shop` - Termékek listája
- `GET /termek/{slug}` - Termék részletes oldala

### Admin:
- `GET /admin/location-product-prices` - Ár mátrix megtekintése
- `PUT /admin/location-product-prices` - Árak frissítése

## Troubleshooting

### Nincs termék a shop oldalon
- Ellenőrizd, hogy ki van-e választva telephely (session)
- Ellenőrizd, hogy van-e beállítva ár az adott telephelyre
- Ellenőrizd, hogy a termékek aktívak-e (`is_active = 1`)

### Árak nem jelennek meg
- Ellenőrizd az adatbázisban a `location_product` táblát
- Ellenőrizd, hogy mind a `gross_price`, mind a `net_price` kitöltött

### Migration hibák
A migrations már lefutottak. Ha újra kell kezdeni:
```bash
php artisan migrate:fresh --seed
```
