<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AuthController;
use App\Models\Department;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Protected employee CRUD
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::get('/departments', function () {
        return Department::select('id', 'name', 'description')->orderBy('name')->get();
    });
});
