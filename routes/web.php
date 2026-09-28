<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});


Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/add-purchase-order', [DashboardController::class, 'addPurchaseOrder'])->name('addPurchaseOrder');

     Route::post('/add-row', [DashboardController::class, 'addRow'])->name('addRow');

      Route::post('/load-product', [DashboardController::class, 'loadProduct'])->name('loadProduct');

       Route::post('/save-purchase-order', [DashboardController::class, 'savePurchaseOrder'])->name('savePurchaseOrder');

        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});
