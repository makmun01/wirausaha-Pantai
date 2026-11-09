<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\PublicController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UmkmController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\FinancialController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\PartnershipController;
use App\Http\Controllers\Web\EisController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\AuditController;

/*
|--------------------------------------------------------------------------
| Public Routes (Portal Wisata & Marketplace Publik)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/tentang-jumiang', [PublicController::class, 'about'])->name('about');
Route::get('/peta-wisata', [PublicController::class, 'map'])->name('map');
Route::get('/katalog-umkm', [PublicController::class, 'catalog'])->name('catalog');
Route::get('/produk/{slug}', [PublicController::class, 'productDetail'])->name('product.detail');
Route::get('/keranjang', [PublicController::class, 'cart'])->name('cart');
Route::get('/reservasi-wisata', [PublicController::class, 'reservationForm'])->name('reservation.form');
Route::post('/reservasi-wisata', [PublicController::class, 'storeReservation'])->name('reservation.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Wisatawan & General Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PublicController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [PublicController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/pesanan-sukses/{invoiceNo}', [PublicController::class, 'orderSuccess'])->name('order.success');
    Route::post('/simulasi-bayar/{invoiceNo}', [PublicController::class, 'confirmPaymentSimulation'])->name('payment.simulate');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Role-Protected Administrative Routes
    |--------------------------------------------------------------------------
    */
    // UMKM & Super Admin: Products, Inventory, Finances
    Route::middleware('role:pelaku_umkm,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('inventory/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.adjust');
        
        // Dedicated UMKM Financial Management (Cash Book & Profit-Loss)
        Route::get('financial', [FinancialController::class, 'index'])->name('financial.index');
        Route::post('financial', [FinancialController::class, 'store'])->name('financial.store');
        Route::get('financial/profit-loss', [FinancialController::class, 'profitLoss'])->name('financial.profit_loss');
    });

    // Orders Management (UMKM & Super Admin)
    Route::middleware('role:pelaku_umkm,super_admin,pengelola_wisata')->prefix('admin')->name('admin.')->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::post('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update_status');
    });

    // Tourism Management & Check-in (Pengelola Wisata & Super Admin)
    Route::middleware('role:pengelola_wisata,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('reservations/check-in', [ReservationController::class, 'validateCheckIn'])->name('reservations.check_in');
    });

    // UMKM Verification & Partnerships (Super Admin)
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('umkm', [UmkmController::class, 'index'])->name('umkm.index');
        Route::post('umkm/{id}/verify', [UmkmController::class, 'verify'])->name('umkm.verify');
        Route::get('partnerships', [PartnershipController::class, 'index'])->name('partnerships.index');
        Route::post('partnerships', [PartnershipController::class, 'store'])->name('partnerships.store');
        Route::get('audit-logs', [AuditController::class, 'index'])->name('audit.index');
    });

    // Executive Information System (EIS) & Strategic Reports (Pimpinan, Super Admin, Bendahara)
    Route::middleware('role:pimpinan,super_admin,bendahara')->prefix('admin')->name('admin.')->group(function () {
        Route::get('eis', [EisController::class, 'index'])->name('eis.index');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
