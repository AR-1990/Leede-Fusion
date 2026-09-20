# PROMPT: Admin Dashboard UI only (Wahla Cloth House)

Naya Cursor chat kholo → project folder: `~/Documents/Wahla-Cloth-House`  
Neeche wala poora prompt paste karo.

---

## Role

You are a frontend UI engineer. Build the **Admin Dashboard UI** for Wahla Cloth House in Laravel Blade + Alpine.js + CSS. Match the look and structure of the working Next.js admin in:

`/Users/mac/Documents/Django-Clothing/frontend/src/app/admin/`  
`/Users/mac/Documents/Django-Clothing/frontend/src/styles/admin-theme.css`

**Focus = UI/layout/styling first.** Wire to existing `/api/*` endpoints. Do not redesign the brand into something generic. Do not invent a purple SaaS theme.

---

## Visual direction (must follow)

### Brand / feel
- Premium retail admin for a cloth house — dark sidebar, light content, gold accent
- Brand name everywhere: **Wahla Cloth House** / **WAHLA ADMIN** (never LEEDE or placeholder brands)
- Fonts: Bebas Neue (headings / labels) + Inter (body) — same as storefront

### Color tokens (use these CSS variables)
```css
--admin-accent: #c9a227;                    /* gold */
--admin-accent-soft: rgba(201, 162, 39, 0.15);
--admin-sidebar: #0a0a0b;                   /* near-black */
--admin-surface: #ffffff;
--admin-border: rgba(15, 23, 42, 0.08);
--admin-text-muted: #64748b;
```

### Layout shell (every admin page)
```
┌────────────┬──────────────────────────────────────┐
│ WAHLA      │  Eyebrow: Wahla Cloth House · Admin  │
│ ADMIN      │  Title + subtitle                    │
│            │  User name · Administrator · Avatar  │
│ Dashboard  ├──────────────────────────────────────┤
│ Products   │                                      │
│ Categories │         PAGE CONTENT                 │
│ Orders     │                                      │
│ Users      │                                      │
│            │                                      │
│ BACK TO    │                                      │
│ STORE      │                                      │
└────────────┴──────────────────────────────────────┘
```

- Left sidebar: dark gradient `#0a0a0b → #12121a`, gold active indicator bar
- Main area: soft grey-blue gradient background `#f1f5f9 → #e8eef5 → #f8fafc`
- Header: frosted white (`backdrop-filter: blur`), page title + user pill
- Content padding ~32–40px
- Hide storefront WhatsApp button + preloader on admin pages

### Copy `admin-theme.css`
Copy from source into `resources/css/admin-theme.css` and load only on admin layout. Reuse class names from the source — do not invent a new design system:

`admin-layout`, `admin-sidebar`, `sidebar-brand`, `sidebar-nav`, `sidebar-link`, `active-indicator`, `admin-main`, `admin-header`, `admin-header-premium`, `admin-content-scroll`, `stats-grid`, `stat-card`, `admin-table`, `admin-table-container`, `admin-surface`, `admin-action-bar`, `btn-admin-primary`, `btn-admin-secondary`, `btn-admin-ghost`, `status-badge`, `admin-input`, `portal-modal-overlay`, `portal-modal-panel`, etc.

---

## Pages — UI specs

### 1. Layout (`layouts/admin.blade.php`)
- Sidebar links: Dashboard, Products, Categories, Orders, Users
- Active link: soft gold background + gold left indicator
- Header: eyebrow, H2 title, muted subtitle, avatar circle with initial
- Footer link: BACK TO STORE → `/`

### 2. Dashboard (`/admin`) — UI
- **4 stat cards** in a responsive grid:
  - TOTAL REVENUE · TOTAL ORDERS · TOTAL CUSTOMERS · AVG ORDER VALUE
- Card style: white surface, soft shadow, uppercase micro-label, large bold value
- **Live Orders table** below: Order ID, Customer, Date, Amount, Status badge, Manage action
- Status badges colored by status (`pending`, `processing`, `shipped`, `out_for_delivery`, `delivered`, `completed`, `cancelled`)
- Empty / loading states: calm muted text, no spinner spam

### 3. Products (`/admin/products`) — UI
- Action bar: short description left, primary button **+ ADD PRODUCT** right
- Toggle add form in `admin-surface` panel:
  - Grid of inputs: Name, Category select, Price, Old price, Tag, Stock, Description
  - Featured checkbox
  - Primary image upload preview (square ~120px)
  - Gallery grid with thumbnails + add tile
- Products table: thumbnail | Name | Category | Price | Stock | Featured YES/NO badge | Delete
- Buttons: primary gold/dark, ghost secondary, red-ish delete as table action

### 4. Categories (`/admin/categories`) — UI
- Same action bar pattern: **+ ADD CATEGORY**
- Create/edit form surface: Name, Slug, Description, Sort order, Image upload
- Table: Image thumb | Name | Slug | Sort | Products count | Edit | Delete
- Edit opens inline panel (same visual language as create)

### 5. Orders (`/admin/orders`) — UI
- Table: Order # | Tracking (mono) | Customer | Date | Amount | Status badge | Manage
- **Manage modal** (wide):
  - Overlay dark blur
  - Ship-to block (name, email, phone, WhatsApp, address)
  - Line items list
  - Status `<select>` + Tracking text input
  - Footer: Cancel + Save (primary)
- Modal must feel premium, not browser-default `alert` chrome

### 6. Users (`/admin/users`) — UI
- Simple table: ID | Name | Email | Role badge (ADMIN / CUSTOMER) | Joined
- No create form needed

---

## UI rules (hard)

1. **One composition per page** — header + one main content job (stats+table OR form+table). No cluttered widget walls.
2. **No generic AI look**: no purple gradients, no Inter-only SaaS cards with glow, no emoji-heavy UI in production chrome (sidebar icons optional/minimal).
3. **Cards only where needed** — stat cards + form surfaces + table containers. Not everything in a card.
4. **Mobile**: sidebar collapses or stacks; tables scroll horizontally; forms stack to 1 column.
5. **Motion**: light only — active sidebar indicator, modal fade/scale, optional stat fade-in. No noisy animations.
6. **Match source** — open the Next.js admin screenshots/code and mirror spacing, typography, and class structure rather than inventing a new dashboard.

---

## Tech for UI

- Blade layouts + partials under `resources/views/admin/`
- Alpine.js for: sidebar active state (server can set), toggle add forms, modal open/close, image preview before upload
- Existing API for data (Bearer token from `$store.auth`)
- `php artisan storage:link` for uploaded image URLs in UI

---

## Deliverables order

1. Admin layout + CSS wired  
2. Dashboard UI (with real or empty-state data)  
3. Products UI (form + table + upload widgets)  
4. Categories UI  
5. Orders UI + manage modal  
6. Users UI  

After each page, stop and show me the URL so I can confirm the UI before the next.

---

## Short Urdu/English one-liner

> Wahla Cloth House ke liye admin dashboard ki **UI** banao Blade + Alpine mein — dark sidebar, gold accent `#c9a227`, light content area. Source: `Django-Clothing/frontend` admin pages + `admin-theme.css`. Pages: Dashboard stats, Products (form+table+image upload), Categories, Orders (manage modal), Users. Brand name Wahla rakho. Pehle layout, phir ek ek page — UI confirm karke next.
