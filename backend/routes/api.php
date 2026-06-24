<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

// TODO: Verificar se podemos remover isso posteriormente.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('produtos', ProductController::class)
    ->only(['index', 'store']);

Route::apiResource('compras', PurchaseOrderController::class)
    ->only(['store']);

Route::apiResource('vendas', SaleController::class)
    ->only(['store', 'update']);
