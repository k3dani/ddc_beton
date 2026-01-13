# Betonas Plius - Laravel Migráció

WordPress → Laravel migrációs projekt a Duna-Dráva Cement (DDC) weboldalához.

## Projekt áttekintés

A WordPress alapú weboldal Laravel 11-re lett átalakítva, megtartva a publikus felület funkcionalitását és megjelenését, de új, egyszerűsített admin felülettel.

## Telepítés és indítás

### 1. Függőségek (már telepítve)
```bash
cd /var/www/betoonpluss/laravel
composer install
npm install
```

### 2. Adatbázis (már konfigurálva)
- Database: `betoonpluss_laravel`
- User: `betoonpluss_laravel`
- Migrációk és seedek futtatva

### 3. Szerver indítása
```bash
php artisan serve
```

## Publikus oldalak

- `/` - Homepage
- `/ecoblock` - ECOBlock termékbemutató
- `/ecocrete` - EcoCrete termékbemutató
- `/magunkrol` - Magunkról oldal
- `/minoseg` - Minőségbiztosítás
- `/shop` - Termékek katalógusa
- `/szallitasi-feltetelek` - Szállítási feltételek
- `/tanacsok` - Tanácsok
- `/ujdonsagok` - Hírek

## Admin felület

**URL:** http://localhost:8000/login

**Bejelentkezés:**
- Email: `admin@dunaconcrete.hu`
- Jelszó: `password`

**Admin modulok:**
- `/admin/dashboard` - Statisztikák
- `/admin/locations` - Telephelyek CRUD
- `/admin/products` - Termékek CRUD
- `/admin/categories` - Kategóriák CRUD

## Adatmodell

### Location (Telephely)
- Név, város, cím, GPS koordináták
- Elérhetőségek (telefon, email)
- Aktív státusz

### Product (Termék)
- Név, slug, SKU, kategória
- Leírások, kép, mértékegység
- Many-to-many kapcsolat Location-nel pivot táblán keresztül

### Location_Product (Pivot - Telephely-specifikus árak)
- **price** - Ár
- **currency** - Pénznem (HUF)
- **is_available** - Elérhetőség

## Mintaadatok

**3 telephely:**
- Vác Betongyár
- Budapest Telephely
- Győr Betonüzem

**3 kategória:**
- Beton
- Betonblokkok
- Speciális termékek

**3 mintaterméknél:**
- Pillérbeton C25/30 F2
- ECOBlock 500x600x600
- ECOBlock 900x600x600

## WordPress → Laravel mapping

**Átvéve:**
✅ 9 menüpont route-okkal
✅ Oldal template struktúra (Blade view-kban)
✅ Shop + termékek + kategóriák
✅ Telephely-alapú árazás

**Egyszerűsítve:**
- ACF mezők → Statikus tartalom Blade-ben
- WooCommerce → Egyszerű product catalog
- Admin → Laravel Breeze + CRUD

## Következő lépések

1. **Asset-ek átemelése:**
```bash
cp -r ../wordpress/wp-content/themes/wam/assets/images/* public/images/
cp -r ../wordpress/wp-content/themes/wam/assets/css/* resources/css/
```

2. **CSS frissítése** - WordPress Bootstrap + custom CSS átportálása

3. **Vite build:**
```bash
npm run build
```

4. **Storage link:**
```bash
php artisan storage:link
```

---

**Laravel:** 11.x | **Dátum:** 2026.01.12
