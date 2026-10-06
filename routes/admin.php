<?php

use App\Http\Controllers\Admin\AnnouncementManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventManagementController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PartnershipController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\SaleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All admin routes are protected by auth + the role:admin middleware.
| Staff cannot access this area — it is admin-only.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', UserManagementController::class)->except(['create', 'store']);

        // Products
        Route::resource('products', ProductManagementController::class);

        // Inventory
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::patch('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
        Route::patch('/inventory/ingredients/{ingredient}/adjust', [InventoryController::class, 'adjustIngredient'])->name('inventory.ingredients.adjust');

        // Orders
        Route::resource('orders', OrderManagementController::class)->only(['index', 'show']);
        Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status');

        // Sales
        Route::resource('sales', SaleManagementController::class)->only(['index', 'show']);

        // Events
        Route::resource('events', EventManagementController::class);

        // Announcements
        Route::resource('announcements', AnnouncementManagementController::class);

        // Partnerships
        Route::resource('partnerships', PartnershipController::class);
        Route::post('/partnerships/{partnership}/members', [PartnershipController::class, 'addMember'])->name('partnerships.members.store');
        Route::delete('/partnerships/{partnership}/members/{member}', [PartnershipController::class, 'removeMember'])->name('partnerships.members.destroy');
    });
