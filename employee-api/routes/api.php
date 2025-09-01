<?php

use App\Http\Controllers\Api\V1\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\EmployeeController as V1EmployeeController;
use App\Http\Controllers\Api\V2\EmployeeController as V2EmployeeController;
use App\Http\Controllers\Api\V1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes (Versioned)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('employees', V1EmployeeController::class);

        Route::get('/departments', [DepartmentController::class, 'index']);
    });
});

Route::prefix('v2')->group(function () {
    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('employees', V2EmployeeController::class);

        Route::get('/departments', [DepartmentController::class, 'index']);
    });
});
