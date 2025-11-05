<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DynamicApiController;

// Dynamic API routes for CRUD tables with token authentication

    Route::get('/v1/{tableName}', [DynamicApiController::class, 'index']);
    Route::post('/v1/{tableName}', [DynamicApiController::class, 'store']);
    Route::get('/v1/{tableName}/{id}', [DynamicApiController::class, 'show']);
    Route::put('/v1/{tableName}/{id}', [DynamicApiController::class, 'update']);
    Route::delete('/v1/{tableName}/{id}', [DynamicApiController::class, 'destroy']);

