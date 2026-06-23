# Laravel API Cleanup — Design Spec

**Date:** 2026-06-23
**Status:** Approved

## Context

The project uses Laravel 13 as a pure REST API backend. The frontend will be built separately with Vue.js in a `frontend/` folder. The Laravel scaffold shipped with web-facing assets (Blade views, CSS, JS, Vite/npm config) that have no role in a pure API backend. This spec describes what to remove and what to change to leave only API-relevant structure.

## Goals

- Remove all web/Blade/frontend artifacts from the backend
- Keep the project runnable and the API functional after cleanup
- Preserve useful dev tooling (Tinker, Pail, Pint)
- Authentication: Laravel Sanctum (already installed)
- CORS: out of scope — to be configured when Vue.js integration begins

## Files to Delete

| Path | Reason |
|---|---|
| `backend/resources/views/welcome.blade.php` | Blade view, no use in API |
| `backend/resources/css/app.css` | Frontend asset |
| `backend/resources/js/app.js` | Frontend asset |
| `backend/routes/web.php` | Web routes — no longer registered |
| `backend/package.json` | Node/Vite toolchain, not needed in backend |
| `backend/package-lock.json` | Lockfile for the above |

The `backend/resources/` directory itself is kept (Laravel may use it for lang files in the future).

## Files to Modify

### `backend/bootstrap/app.php`

Remove the `web:` route registration:

```php
// Before
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)

// After
->withRouting(
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

### `backend/composer.json`

Remove npm references from the `scripts` block:

- `setup`: remove `"npm install --ignore-scripts"` and `"npm run build"`
- `dev`: replace the `concurrently` command with a simple `php artisan serve`

## What Stays

| What | Why |
|---|---|
| `routes/api.php` with `GET /api/health` | API entry point and health check |
| `laravel/sanctum` + personal access tokens migration | API authentication |
| `laravel/tinker` | Interactive debugging inside the container |
| `laravel/pail`, `laravel/pint` (dev) | Log tailing and code formatting |
| `bootstrap/app.php` `health: '/up'` | Laravel native health check endpoint |
| `app/`, `database/`, `config/`, `tests/` | Full Laravel core structure |

## Health Check Endpoints

After cleanup, two health endpoints will exist:

- `GET /up` — Laravel native (registered via `health: '/up'` in bootstrap)
- `GET /api/health` — custom endpoint in `routes/api.php`, returns `{"status":"ok"}`

Both can coexist. The custom one can be removed later if redundant.

## Out of Scope

- CORS configuration (deferred to Vue.js integration phase)
- Any new API routes or business logic
- Docker or Nginx changes
