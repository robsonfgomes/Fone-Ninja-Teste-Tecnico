# Laravel API Cleanup Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove all web/frontend artifacts from the Laravel backend, leaving a pure API project.

**Architecture:** Delete Blade/CSS/JS resource files, remove `routes/web.php` and unregister it from `bootstrap/app.php`, remove `package.json`, and strip npm references from `composer.json` scripts. Update the existing Feature test to cover the API health endpoint instead of the now-removed web root.

**Tech Stack:** Laravel 13, PHP 8.3, Laravel Sanctum, PHPUnit 12, Docker (container: `fone_ninja_erp_app`)

## Global Constraints

- Never modify files inside `vendor/`
- All `php artisan` commands run inside the Docker container: `docker exec fone_ninja_erp_app <cmd>`
- Tests must pass after every task — run with `docker exec fone_ninja_erp_app php artisan test`
- Do not remove Tinker, Pail, or Pint packages

---

## File Map

| Action | Path | What changes |
|---|---|---|
| Modify | `backend/tests/Feature/ExampleTest.php` | Test `GET /api/health` instead of `GET /` |
| Delete | `backend/resources/views/welcome.blade.php` | Blade view — no use in API |
| Delete | `backend/resources/css/app.css` | Frontend CSS |
| Delete | `backend/resources/js/app.js` | Frontend JS |
| Delete | `backend/routes/web.php` | Web routes file |
| Delete | `backend/package.json` | Node/Vite toolchain |
| Modify | `backend/bootstrap/app.php` | Remove `web:` line from `withRouting()` |
| Modify | `backend/composer.json` | Strip npm from `setup` and `dev` scripts |

---

### Task 1: Update Feature test to cover the API

The existing `ExampleTest` hits `GET /` which will 404 after we remove the web route. Update it now (before any deletions) so tests stay green throughout.

**Files:**
- Modify: `backend/tests/Feature/ExampleTest.php`

- [ ] **Step 1: Update the test**

Replace the full contents of `backend/tests/Feature/ExampleTest.php` with:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_health_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
                 ->assertJson(['status' => 'ok']);
    }
}
```

- [ ] **Step 2: Run tests — expect PASS**

```bash
docker exec fone_ninja_erp_app php artisan test
```

Expected output includes:
```
PASS  Tests\Feature\ExampleTest
✓ health endpoint returns ok
PASS  Tests\Unit\ExampleTest
✓ that true is true
```

- [ ] **Step 3: Commit**

```bash
git add backend/tests/Feature/ExampleTest.php
git commit -m "test: point feature test to /api/health"
```

---

### Task 2: Remove frontend resource files and web route

Delete Blade views, CSS, JS, and the web routes file. Unregister the web route from bootstrap.

**Files:**
- Delete: `backend/resources/views/welcome.blade.php`
- Delete: `backend/resources/css/app.css`
- Delete: `backend/resources/js/app.js`
- Delete: `backend/routes/web.php`
- Modify: `backend/bootstrap/app.php`

- [ ] **Step 1: Delete the frontend resource files**

```bash
rm backend/resources/views/welcome.blade.php
rm backend/resources/css/app.css
rm backend/resources/js/app.js
rm backend/routes/web.php
```

- [ ] **Step 2: Remove the `web:` line from `bootstrap/app.php`**

In `backend/bootstrap/app.php`, change the `withRouting()` call from:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

to:

```php
->withRouting(
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

- [ ] **Step 3: Run tests — expect PASS**

```bash
docker exec fone_ninja_erp_app php artisan test
```

Expected output:
```
PASS  Tests\Feature\ExampleTest
✓ health endpoint returns ok
PASS  Tests\Unit\ExampleTest
✓ that true is true
```

- [ ] **Step 4: Verify health endpoints still respond**

```bash
curl -s http://localhost:8084/api/health
# Expected: {"status":"ok"}

curl -s http://localhost:8084/up
# Expected: HTTP 200 (Laravel native health check)
```

- [ ] **Step 5: Commit**

```bash
git add backend/resources/views/welcome.blade.php \
        backend/resources/css/app.css \
        backend/resources/js/app.js \
        backend/routes/web.php \
        backend/bootstrap/app.php
git commit -m "chore: remove web/frontend artifacts from Laravel API"
```

---

### Task 3: Remove npm artifacts and clean composer scripts

Delete `package.json` and strip npm/Vite references from `composer.json` scripts.

**Files:**
- Delete: `backend/package.json`
- Modify: `backend/composer.json`

- [ ] **Step 1: Delete package.json**

```bash
rm backend/package.json
```

- [ ] **Step 2: Update `composer.json` scripts**

In `backend/composer.json`, replace the `setup` and `dev` script values:

`setup` — remove `npm install --ignore-scripts` and `npm run build`:

```json
"setup": [
    "composer install",
    "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
    "@php artisan key:generate",
    "@php artisan migrate --force"
],
```

`dev` — replace the full `concurrently` command with individual artisan commands:

```json
"dev": [
    "Composer\\Config::disableProcessTimeout",
    "@php artisan serve",
    "@php artisan queue:listen --tries=1 --timeout=0",
    "@php artisan pail --timeout=0"
],
```

- [ ] **Step 3: Run tests — expect PASS**

```bash
docker exec fone_ninja_erp_app php artisan test
```

Expected output:
```
PASS  Tests\Feature\ExampleTest
✓ health endpoint returns ok
PASS  Tests\Unit\ExampleTest
✓ that true is true
```

- [ ] **Step 4: Commit**

```bash
git add backend/package.json backend/composer.json
git commit -m "chore: remove npm/Vite artifacts and clean composer scripts"
```
