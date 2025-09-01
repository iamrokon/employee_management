<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Models\Department;

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
        Route::apiResource('employees', EmployeeController::class);

        Route::get('/departments', function () {
            return Department::select('id', 'name', 'description')
                ->orderBy('name')
                ->get();
        });
    });
});
