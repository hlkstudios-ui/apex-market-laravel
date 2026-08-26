# Apex Market

A complete Laravel 13 shopping application with a responsive catalog, search and filters, product pages, session cart, validated checkout, transactional orders, inventory tracking, and seeded demo data.

## Run locally

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install && npm run build
composer run dev
```

Open `http://localhost:8000`. SQLite is configured by default.

## Test

```bash
php artisan test
```

Prices use integer cents. Checkout saves orders locally; connect a payment provider before accepting real payments.
