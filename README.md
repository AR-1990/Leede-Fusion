# Wahla Cloth House (Unified Laravel)

Single Laravel application: Blade storefront + existing API controllers.

## Structure

- `routes/web.php` — Blade pages (Home migrated; more pages next)
- `routes/api.php` — unchanged API (auth, catalog, orders, admin)
- `resources/views/` — Blade layouts, partials, sections
- `resources/js/app.js` — Alpine.js stores (`auth`, `cart`) + UI widgets
- `resources/css/storefront.css` — migrated from Next.js `globals.css`
- `app/Http/Controllers/` — API controllers reused; `Web/HomeController` for views
- `config/content.php` — static marketing content (hero slides, why shop, contact)

## Setup

```bash
cd laravel
composer install
cp .env.example .env
php artisan key:generate
# configure DB in .env, then:
php artisan migrate --seed
php artisan storage:link
npm install
npm run build   # or: npm run dev
php artisan serve
```

Open http://127.0.0.1:8000

## Migration progress

| Page | Status |
|------|--------|
| Home | Done |
| Collections | Pending |
| Product detail / Quick view | Quick view on Home |
| Cart / Checkout | Pending |
| Auth (login/register) | Pending |
| Profile / Orders | Pending |
| Admin | Pending |

Original `frontend/` (Next.js) and `backend/` (API-only) remain in the repo until migration is complete.
