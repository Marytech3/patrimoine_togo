<?php

use App\Http\Controllers\Api\AssetController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', fn () => response()->json(['service' => 'asset-service', 'status' => 'ok']));
    Route::get('/summary', [AssetController::class, 'summary']);
    Route::apiResource('assets', AssetController::class);
});
