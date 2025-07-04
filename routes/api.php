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

    // admin routes
    Route::group(['prefix' => 'admin'], function() {
        
        // integrations
        Route::group(['prefix' => 'integrations'], function() {
            Route::get('/', [IntegrationController::class, 'indexAdmin']);
            Route::post('/', [IntegrationController::class, 'store']);
            Route::post('/{integration}', [IntegrationController::class, 'update']);
            Route::delete('/{integration}', [IntegrationController::class, 'destroy']);
            Route::patch('/{integration}/toggle-status', [IntegrationController::class, 'toggleStatus']);
        });

        // feautres
        Route::group(['prefix' => 'features'], function() {
            Route::get('/', [FeatureController::class, 'index']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::get('/{feature}', [FeatureController::class, 'show']);
            Route::put('/{feature}', [FeatureController::class, 'update']);
            Route::delete('/{feature}', [FeatureController::class, 'destroy']);
            Route::patch('/{feature}/toggle-status', [FeatureController::class, 'toggleStatus']);
        });

        // plans
        Route::group(['prefix' => 'plans'], function() {
            Route::get('/', [PlanController::class, 'indexAdmin']);
            Route::post('/', [PlanController::class, 'store']);
            Route::get('/{plan}', [PlanController::class, 'show']);
            Route::put('/{plan}', [PlanController::class, 'update']);
            Route::delete('/{plan}', [PlanController::class, 'destroy']);
            Route::patch('/{plan}/toggle-status', [PlanController::class, 'toggleStatus']);
        });

    });

});

// Public integration routes
Route::get('integrations', [IntegrationController::class, 'indexPublic']);

// Public plans listing
Route::get('plans', [PlanController::class, 'indexPublic']);
