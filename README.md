# TequilaPOS — Laravel

Real Laravel 11 project that serves the TequilaPOS public website.

## Architecture

- **Laravel 11** routes, controllers, and Blade views with proper SEO meta per page (`/`, `/menu`, `/about`, `/login`, `/register`, `/account`, `/profile`).
- The interactive UI (cart, menu, modifiers, checkout, auth, order tracking, profile editing, receipt PDF) is delivered as a precompiled bundle under `public/assets/`. This is the same UI you see in the Lovable preview — pixel-identical.
- All business data is fetched directly from the **TequilaPOS API** (`https://tequilapos.net/api`) from the browser. Restaurant ID is auto-detected from the subdomain (defaults to `43` on localhost).
- No database is needed. Cart/auth state is persisted in `localStorage`.

## Setup

```bash
composer install
php artisan key:generate
php artisan serve
# open http://localhost:8000
```

For Apache / Nginx, point the document root at `public/`.

## Pages & routes

| Route        | Controller method            | View                           |
|--------------|-------------------------------|--------------------------------|
| `/`          | `PageController@home`         | `pages/home.blade.php`         |
| `/menu`      | `PageController@menu`         | `pages/menu.blade.php`         |
| `/about`     | `PageController@about`        | `pages/about.blade.php`        |
| `/login`     | `PageController@login`        | `pages/login.blade.php`        |
| `/register`  | `PageController@register`     | `pages/register.blade.php`     |
| `/account`   | `PageController@account`      | `pages/account.blade.php`      |
| `/profile`   | `PageController@profile`      | `pages/profile.blade.php`      |
| `/{any}`     | `PageController@spa` (fallback) | `pages/home.blade.php`       |

Each Blade view extends `layouts/app.blade.php` which sets the page title, description, OG/Twitter meta, canonical URL, and loads the compiled CSS/JS bundle.

## Database / Migrations

This frontend does not use a local database — everything goes through your TequilaPOS API and your existing admin panel. No migrations or dummy data are included by design.
