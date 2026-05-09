# TequilaPOS — Laravel Online Ordering

A Laravel 11 restaurant website for TequilaPOS customers. The application is now rendered by PHP controllers and Blade templates instead of a client-side SPA shell.

## Architecture

- **Laravel 11** routes, controllers, middleware, services, and Blade views render the public website (`/`, `/menu`, `/about`) and customer flows (`/login`, `/register`, `/account`, `/profile`, `/orders`).
- **Server-side API loading:** controllers call `App\Services\TequilaPosClient`, which reads from `https://tequilapos.net/api`, unwraps common API envelopes, and caches read-only GET requests for 5 minutes.
- **Dynamic restaurant resolution:** the first subdomain label is used as the restaurant slug. For example, `tequilatexas.tequilapos.com` resolves `tequilatexas`, calls `/website-restaurant-id/tequilatexas`, and falls back to restaurant `43` on localhost or lookup failure.
- **No local business database:** menu, settings, gallery, customers, and orders remain TequilaPOS API-backed. Laravel sessions store cart lines, customer auth token, and flash messages.
- **Blade-first UI:** Tailwind Play CDN and Alpine.js provide styling and small interactions such as the cart drawer; the compiled Lovable React bundle is no longer mounted by the Laravel layout.

## Setup

```bash
composer install
cp .env.example .env # if needed
php artisan key:generate
php artisan serve
# open http://localhost:8000
```

For Apache / Nginx, point the document root at `public/`.

## Important files

| Area | Files |
| --- | --- |
| Routes | `routes/web.php` |
| API client | `app/Services/TequilaPosClient.php` |
| Cart session service | `app/Services/CartService.php` |
| Customer session auth | `app/Services/CustomerAuthService.php` |
| Global view sharing | `app/Http/Middleware/ShareGlobals.php` |
| Customer route guard | `app/Http/Middleware/AuthenticateCustomer.php` |
| Layout and partials | `resources/views/layouts/app.blade.php`, `resources/views/partials/*.blade.php` |
| Pages | `resources/views/pages`, `resources/views/auth`, `resources/views/account` |

## Configuration

The default API settings live in `config/services.php` and can be overridden with environment variables:

```env
TEQUILAPOS_BASE_URL=https://tequilapos.net/api
TEQUILAPOS_IMAGE_BASE=https://tequilapos.net/
TEQUILAPOS_AUTH_KEY=4446760d-1aae-486d-bc24-d175eb934395
TEQUILAPOS_DEFAULT_RESTAURANT_ID=43
```

## Notes

- Password reset remains informational because the public API does not expose a reset endpoint yet.
- Checkout submits the session cart to the TequilaPOS create-order endpoint and does not integrate a card processor locally.
- The old compiled React assets remain in `public/assets/` for reference/static files, but Laravel no longer relies on them to render the website.
