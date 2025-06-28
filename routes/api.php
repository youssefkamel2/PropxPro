<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FeatureController;
use App\Http\Controllers\Api\IntegrationController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

// Protected routes
Route::group(['middleware' => ['auth:api']], function () {
    // Superadmin routes for managing admins
    Route::group(['middleware' => ['role:superadmin'], 'prefix' => 'admins'], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/', [AdminController::class, 'store']);
        Route::post('/{admin}', [AdminController::class, 'update']);
        Route::patch('/{admin}/toggle-status', [AdminController::class, 'toggleStatus']);
        Route::delete('/{admin}', [AdminController::class, 'destroy']);

        // Permission management
        Route::get('/permissions', [AdminController::class, 'listAvailablePermissions']);
        Route::put('/{admin}/permissions', [AdminController::class, 'updatePermissions']);
    });

    // Integration management routes (protected by permissions)
    Route::group(['prefix' => 'admin/integrations'], function () {
        Route::get('/', [IntegrationController::class, 'indexAdmin']);
        Route::post('/', [IntegrationController::class, 'store']);
        Route::post('/{integration}', [IntegrationController::class, 'update']);
        Route::delete('/{integration}', [IntegrationController::class, 'destroy']);
        Route::patch('/{integration}/toggle-status', [IntegrationController::class, 'toggleStatus']);
    });

    // Feature and Plan management (admin)
    Route::group(['prefix' => 'admin'], function () {
        Route::get('features', [FeatureController::class, 'index']);
        Route::post('features', [FeatureController::class, 'store']);
        Route::get('features/{feature}', [FeatureController::class, 'show']);
        Route::post('features/{feature}', [FeatureController::class, 'update']);
        Route::delete('features/{feature}', [FeatureController::class, 'destroy']);
        Route::patch('features/{feature}/toggle-status', [FeatureController::class, 'toggleStatus']);

        // Plan management
        Route::get('plans', [PlanController::class, 'index']);
        Route::post('plans', [PlanController::class, 'store']);
        Route::get('plans/{plan}', [PlanController::class, 'show']);
        Route::post('plans/{plan}', [PlanController::class, 'update']);
        Route::delete('plans/{plan}', [PlanController::class, 'destroy']);
        Route::patch('plans/{plan}/toggle-status', [PlanController::class, 'toggleStatus']);
    });
});

// Public integration routes
Route::get('integrations', [IntegrationController::class, 'indexPublic']);

// Public plans listing
Route::get('plans', [PlanController::class, 'index']);
