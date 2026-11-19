<?php

use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AssetMovementController;
use App\Http\Controllers\Api\GovernmentServiceController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', fn () => response()->json(['service' => 'asset-service', 'status' => 'ok']));
    Route::get('/summary', [AssetController::class, 'summary']);
    Route::get('/services', [GovernmentServiceController::class, 'index']);
    Route::post('/services', [GovernmentServiceController::class, 'store']);
    Route::put('/services/{service}', [GovernmentServiceController::class, 'update']);
    Route::patch('/services/{service}', [GovernmentServiceController::class, 'update']);
    Route::get('/movements', [AssetMovementController::class, 'index']);
    Route::post('/movements', [AssetMovementController::class, 'store']);
    Route::get('/reports/summary', [ReportController::class, 'summary']);
    Route::apiResource('assets', AssetController::class);
});
