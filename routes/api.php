<?php

use App\Http\Api\Controllers\AuthController;
use App\Http\Api\Controllers\CategoriesController;
use App\Http\Api\Controllers\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('users/logout', [AuthController::class, 'logout']);

    //categories
    Route::POST('categories/{category}', [CategoriesController::class, 'update']);
    Route::apiResource('categories', CategoriesController::class)->except(['PUT', 'PATCH']);

    //permissions
    Route::get('permissions', [PermissionController::class, 'index']);
});


//authentication

Route::post('users/login', [AuthController::class, 'login']);
Route::post('users/register', [AuthController::class, 'register']);
