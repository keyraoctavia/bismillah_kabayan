# Teskabayan — Aplikasi Kasir & Manajemen Stok

Aplikasi berbasis Laravel untuk kasir (POS), manajemen produk, gudang, dan barang masuk (stok).

## Fitur Utama

- **Kasir** — transaksi penjualan & checkout
- **Barang Masuk** — pencatatan stok masuk per gudang
- **Manajemen Produk** — CRUD produk (khusus admin)
- **Manajemen Gudang** — CRUD data gudang (khusus admin)
- **Autentikasi & Role** — login, profil user, hak akses admin

## Tech Stack

- Laravel 13 (PHP ^8.3)
- Vite + Tailwind CSS 4
- MySQL/MariaDB (default Laravel)

## Instalasi

```bash
git clone <url-repo>
cd teskabayan

composer install
npm install

cp .env.example .env
php artisan key:generate


php artisan migrate


php artisan serve
```

## Struktur Singkat

```
app/Http/Controllers/   # CashierController, ProductController, StockInController, WarehouseController, dll
app/Models/              # Product, Transaction, TransactionDetail, StockIn, StockInDetail, Warehouse, User
database/migrations/     # skema tabel: products, transactions, stock_ins, warehouses, dll
routes/web.php           # daftar route aplikasi
```

## Role & Akses

- **User biasa**: akses kasir, barang masuk, profil
- **Admin**: tambahan akses ke manajemen produk & gudang

