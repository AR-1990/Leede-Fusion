# PROMPT: Finish migrating Wahla Cloth House → unified Laravel (Blade) — INCLUDE FULL ADMIN

Copy everything below the line into a new Cursor chat (open the project folder `~/Documents/Wahla-Cloth-House`).

---

## Context

Two codebases exist:

1. **Source (working full app)**  
   `/Users/mac/Documents/Django-Clothing`  
   - `frontend/` = Next.js storefront + **full dynamic Admin Portal**  
   - `backend/` = Laravel API (models, migrations, controllers, Sanctum, seeders) — already reused

2. **Target (incomplete unified Laravel)**  
   `/Users/mac/Documents/Wahla-Cloth-House`  
   - Blade storefront partially done (Home and some pages)  
   - API controllers already present under `app/Http/Controllers/` and `routes/api.php`  
   - **Admin is NOT feature-complete** — only a stub/partial dashboard exists. Missing: products CRUD, categories CRUD, image/gallery upload, stock, featured flag, order tracking/status management, users list, real admin layout/theme.

**Do not rebuild the API.** Reuse existing Laravel API routes/controllers as-is. Build Blade + Alpine.js (or vanilla JS) admin UI that calls the same `/api/*` endpoints (or wire web controllers that return Blade views and still use the same models).

## Goal

Port the **entire dynamic Admin Dashboard** from the Next.js app into `Wahla-Cloth-House` with **feature parity**, so an admin can:

- Log in as admin and access `/admin` (non-admins blocked)
- See dashboard stats + recent orders
- **Create / list / delete products** (name, category, price, old price, description, tag, **stock**, **is_featured**, primary image, gallery images)
- **Create / edit / delete categories** (name, slug, description, image, sort_order)
- **Upload images** to storage via `POST /api/admin/media` (primary + gallery)
- **Track and manage orders**: list orders, open detail modal, update **status** + **tracking_number**
- **List users** (name, email, admin/customer role)
- Keep storefront styling/admin theme look from the source (`frontend/src/styles/admin-theme.css`)

## Source files to port (must read these)

### Admin pages (Next.js → Blade)
- `Django-Clothing/frontend/src/app/admin/layout.tsx` → Blade admin layout + sidebar
- `Django-Clothing/frontend/src/app/admin/page.tsx` → Dashboard
- `Django-Clothing/frontend/src/app/admin/products/page.tsx` → Products CRUD UI
- `Django-Clothing/frontend/src/app/admin/categories/page.tsx` → Categories CRUD UI
- `Django-Clothing/frontend/src/app/admin/orders/page.tsx` → Orders + manage modal
- `Django-Clothing/frontend/src/app/admin/users/page.tsx` → Users list
- Settings page is informational only — optional / skip or replace with Laravel notes

### Admin components
- `frontend/src/components/admin/ImageUploadField.tsx`
- `frontend/src/components/admin/GalleryUploadField.tsx`

### Styles & helpers
- `frontend/src/styles/admin-theme.css` → copy into `resources/css/admin-theme.css` and load on admin layout
- `frontend/src/lib/orderStatus.ts` → PHP helper or JS constants (`pending`, `processing`, `shipped`, `out_for_delivery`, `delivered`, `completed`, `cancelled`)

### Working API (already in target — do not rewrite)
- `POST /api/login`, `POST /api/logout`
- `GET|POST|DELETE /api/products`, `PUT|PATCH /api/products/{id}` if used
- `GET|POST|PATCH|DELETE /api/categories`
- `GET /api/orders`, `GET /api/orders/{id}`, `PATCH /api/admin/orders/{id}`
- `GET /api/admin/users`
- `POST /api/admin/media` (multipart `file` + `folder`: `categories|products|gallery`)
- Middleware: `auth:sanctum` + `admin` (`EnsureUserIsAdmin`)

## Required admin routes (web)

Create Blade routes like:

- `GET /admin` → dashboard  
- `GET /admin/products` → products page  
- `GET /admin/categories` → categories page  
- `GET /admin/orders` → orders page  
- `GET /admin/users` → users page  

Protect with admin middleware (session auth OR Sanctum token in Alpine — match whatever login already uses in Wahla-Cloth-House). Prefer one consistent approach; if storefront login already stores `wahla_token` / `wahla_user` in localStorage, keep that for admin API calls (same as Next.js).

## UI / behavior requirements (parity checklist)

### Layout
- [ ] Sidebar: Dashboard, Products, Categories, Orders, Users (+ Back to Store)
- [ ] Admin-only gate: redirect guests to `/login`, non-admins to `/`
- [ ] Load `admin-theme.css`; hide storefront WhatsApp/preloader on admin pages

### Dashboard
- [ ] Stats: total revenue, orders, customers (or products), avg order value
- [ ] Recent/live orders table with status badges + link/manage

### Products
- [ ] List with image thumb, name, category, price, stock, featured, delete
- [ ] Add form: all fields including stock + featured checkbox
- [ ] ImageUploadField + GalleryUploadField equivalents (Alpine/vanilla) posting to `/api/admin/media`
- [ ] Create via `POST /api/products`, delete via `DELETE /api/products/{id}`

### Categories
- [ ] List with image, name, slug, sort, products_count
- [ ] Create + Edit + Delete
- [ ] Image upload for category cover
- [ ] Surface server error when deleting category that still has products

### Orders
- [ ] Table: id, tracking, customer, date, amount, status, Manage
- [ ] Manage modal: ship-to details, line items, status select (7 statuses), tracking input
- [ ] Save via `PATCH /api/admin/orders/{id}` with `{ status, tracking_number }`
- [ ] Support `?manage={id}` deep link if easy

### Users
- [ ] Read-only table: id, name, email, role (ADMIN/CUSTOMER), joined date
- [ ] `GET /api/admin/users`

### Media
- [ ] `php artisan storage:link` documented/required
- [ ] Uploads return public URL usable in product/category `image` / `images`

## Explicitly NOT done yet / known gaps in target

- Full Products admin page with create + image/gallery + stock + featured  
- Full Categories admin page with create/edit/delete + image  
- Full Orders admin with status + tracking updates  
- Users admin page  
- Real admin layout matching `admin-theme.css` (current stub may say wrong brand names — fix to **Wahla Cloth House**)  
- Image/gallery upload components  

## Constraints

- Single Laravel project only (`Wahla-Cloth-House`) — no Next.js  
- Do **not** change DB schema, models, or seeders unless a bug requires a tiny fix  
- Preserve API contracts so existing tokens/admin user still work (`admin@wahla.test` / `password` from seeder)  
- Use Blade + Alpine.js (already used on storefront) instead of React  
- Work incrementally: Layout → Dashboard → Products → Categories → Orders → Users; confirm each works before the next  

## Done when

An admin can open `/admin`, manage catalog (categories + products + images + stock), and update order status/tracking end-to-end without using the old Next.js frontend.

---

## Optional one-liner (short version)

> Finish migrating the **full dynamic Admin Portal** from `/Users/mac/Documents/Django-Clothing/frontend/src/app/admin/*` into `/Users/mac/Documents/Wahla-Cloth-House` as Blade + Alpine pages. API already exists — do not rebuild it. Port layout, dashboard, products (CRUD + stock + featured + image/gallery upload via `/api/admin/media`), categories (CRUD + image), orders (status + tracking via `PATCH /api/admin/orders/{id}`), and users list. Copy `admin-theme.css`. Feature parity with the working Next.js admin; fix any stub “LEEDE” branding to Wahla Cloth House.
