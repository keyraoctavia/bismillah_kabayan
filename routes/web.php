<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/kasir/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');

    Route::get('/barang-masuk', [StockInController::class, 'index'])->name('stock-ins.index');
    Route::get('/barang-masuk/create', [StockInController::class, 'create'])->name('stock-ins.create');
    Route::post('/barang-masuk', [StockInController::class, 'store'])->name('stock-ins.store');
    Route::get('/barang-masuk/{stockIn}', [StockInController::class, 'show'])->name('stock-ins.show');

    Route::get('/transfer-barang', [StockTransferController::class, 'index'])->name('stock-transfers.index');
    Route::get('/transfer-barang/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
    Route::post('/transfer-barang', [StockTransferController::class, 'store'])->name('stock-transfers.store');
    Route::get('/transfer-barang/{stockTransfer}', [StockTransferController::class, 'show'])->name('stock-transfers.show');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('warehouses', WarehouseController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transaksi/{transaction}/batalkan', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    Route::post('/barang-masuk/{stockIn}/batalkan', [StockInController::class, 'cancel'])->name('stock-ins.cancel');

    Route::post('/transfer-barang/{stockTransfer}/batalkan', [StockTransferController::class, 'cancel'])->name('stock-transfers.cancel');
});