# Házas Kép Kategória Pozicionálás - Dokumentáció

## Áttekintés

Ez a funkció lehetővé teszi, hogy a beton kategóriák interaktívan pozicionálhatóak legyenek a rendelési oldal házas képén, "plusz" jelekkel jelölve. A kategóriák, amelyek nem férnek el a képen, egy külön szekcióban jelennek meg a kép alatt.

## Adatbázis Struktúra

### product_categories tábla új mezői:
- `position_x` (STRING, nullable) - X pozíció %-ban (pl: "48%")
- `position_y` (STRING, nullable) - Y pozíció %-ban (pl: "7%")
- `is_visible_on_house` (BOOLEAN, default: false) - Megjelenjen-e a házas képen

## Admin Felület Használata

### Kategória Pozíció Beállítása

1. Lépjen be az admin felületre: `/admin/categories`
2. Válassza ki a szerkeszteni kívánt kategóriát
3. Jelölje be a **"Megjelenítés a házas képen"** checkbox-ot
4. Kattintson a házas képre a kívánt pozíción
5. A plusz jel előnézete megjelenik a kiválasztott helyen
6. Kattintson a **"Frissítés"** gombra a mentéshez

### Pozíció Módosítása

- Csak kattintson újra a képre egy új pozícióban
- A marker pozíciója frissül
- Mentés után az új pozíció él

### Kategória Eltávolítása a Képről

- Vegye ki a pipát a **"Megjelenítés a házas képen"** checkbox-ból
- Mentés után a kategória a kép alatti listában fog megjelenni

## Frontend Működés

### Házas Képen
- A kategóriák plusz jeles info-boxokban jelennek meg
- **Hover** hatás: Az info-box kibővül és megmutatja a kategória nevét
- **Kattintás**: A lenyíló lista kibomlik a kategóriába tartozó termékekkel
- **Termékek**: Thumbnail képpel és árral jelennek meg
- **Disabled állapot**: Ha nincs elérhető termék, a plusz jel szürkébb és átlátszóbb

### Kép Alatti Szekció
- Azok a kategóriák, amelyek `is_visible_on_house = false`
- Grid layoutban, card-okként jelennek meg
- Minden card tartalmazza:
  - Kategória név
  - Leírás (rövidített)
  - Termékek száma
  - "Termékek megtekintése" gomb (ha vannak termékek)
  - Disabled státusz (ha nincsenek termékek)

## Kód Áttekintés

### Migráció
```bash
php artisan migrate
```
Hozzáadja a három új mezőt a `product_categories` táblához.

### Model (ProductCategory)
```php
protected $fillable = [
    'position_x',
    'position_y', 
    'is_visible_on_house',
];

protected $casts = [
    'is_visible_on_house' => 'boolean',
];
```

### Controller (ShopController)
```php
// Kategóriák eager loadinggal
$allCategories = ProductCategory::with(['products' => function($q) use ($selectedLocation) {
    // Csak elérhető termékek
}])->ordered()->get();

// Szétválasztás
$categoriesOnHouse = $allCategories->where('is_visible_on_house', true);
$categoriesBelowHouse = $allCategories->where('is_visible_on_house', false);
```

### View (shop.blade.php)
```blade
@foreach($categoriesOnHouse as $houseCategory)
    <div class="info-box {{ !$hasProducts ? 'disabled' : '' }}" 
         style="left: {{ $houseCategory->position_x }}; top: {{ $houseCategory->position_y }};">
        <!-- Info box tartalom -->
    </div>
@endforeach
```

## Seeder Adatok

A DatabaseSeeder 10 kategóriát tartalmaz:
- **7 kategória a házas képen**: Pillérbeton, Födémbeton, Lépcsőbeton, Aljzatbeton, Falazatbeton, Alapbeton, Járdabeton
- **3 kategória a kép alatt**: Medencebeton, Betonblokkok, Speciális termékek

Minden kategóriához tartoznak termékek különböző telephelyekkel és árakkal.

## Responsivitás

- A pozíciók %-ban vannak tárolva, így minden képmérethez igazodnak
- A CSS vw/vh egységeket használ a méretezéshez
- Mobile nézeten optimalizálható külön

## Jövőbeli Fejlesztési Lehetőségek

1. **Drag & Drop Admin**: Real-time drag & drop pozicionálás az admin felületen
2. **Kategória Ikonok**: Egyedi ikonok a plusz jel helyett
3. **Animációk**: Smooth transitions az info-boxok között
4. **Mobile Layout**: Külön layout kisebb képernyőkön
5. **Bulk Edit**: Több kategória pozíciójának egyszerre történő szerkesztése
6. **Preview Mode**: Admin preview a mentés előtt

## Troubleshooting

### A kategória nem jelenik meg a képen
- Ellenőrizze, hogy `is_visible_on_house = true`
- Ellenőrizze, hogy van-e `position_x` és `position_y` érték
- Ellenőrizze, hogy a pozíció értékek érvényesek-e (pl: "48%")

### A termékek nem láthatóak
- Ellenőrizze, hogy van-e kiválasztva telephely a session-ben
- Ellenőrizze, hogy a termékhez tartozik-e ár az adott telephelyen
- Ellenőrizze az `is_available` értékét a `location_product` táblában

### A JavaScript nem működik
- Ellenőrizze, hogy a `scripts.js` be van-e töltve
- Ellenőrizze a konzolt hibák után
- Ellenőrizze, hogy az info-box osztályok helyesen vannak-e generálva

## Kapcsolódó Fájlok

- **Migráció**: `database/migrations/2026_02_03_093350_add_position_fields_to_product_categories_table.php`
- **Model**: `app/Models/ProductCategory.php`
- **Controller**: `app/Http/Controllers/ShopController.php`
- **Admin Controller**: `app/Http/Controllers/Admin/ProductCategoryController.php`
- **View**: `resources/views/pages/shop.blade.php`
- **Admin View**: `resources/views/admin/categories/edit.blade.php`
- **Seeder**: `database/seeders/DatabaseSeeder.php`
- **JavaScript**: `public/js/scripts.js`
- **CSS**: `public/css/style.css`
