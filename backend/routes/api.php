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

Route::prefix('produtos')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
});

Route::post('compras', [PurchaseOrderController::class, 'store']);

Route::prefix('vendas')->group(function () {
    Route::post('/', [SaleController::class, 'store']);
    Route::patch('/{venda}', [SaleController::class, 'update']);
});
