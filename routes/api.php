<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Container\Attributes\Log;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\PurchaseOrderController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/inventory', [InventoryController::class, 'inventory']);

    Route::get('/inventory-low-stock', [InventoryController::class, 'inventoryLowStock']);

     Route::post('/purchase-orders', [PurchaseOrderController::class, 'savePurchaseOrder']);
     Route::patch('/purchase-orders/{id}', [PurchaseOrderController::class, 'updatePurchaseOrder']);

      Route::get('/reports/supplier-spend', [PurchaseOrderController::class, 'ordersBySupplier']);
});
