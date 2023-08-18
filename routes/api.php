<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\UserAccountController;
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

/**
 * User Routes
 */
Route::post('/v1/user/login', [UserAuthController::class, 'login']);

Route::group(['prefix' => 'v1/user', 'middleware' => 'auth:api'], function () {
    Route::get('', []);
    Route::delete('', []);
    Route::get('orders', []);
    Route::post('create', []);
    Route::post('forgot-password', []);
    Route::get('logout', []);
    Route::post('reset-password-token', []);
    Route::put('edit', []);
});

/**
 * Admin Routes
 */
Route::post('/v1/admin/login', [AdminAuthController::class, 'login']);

Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth:api', 'admin']], function () {

    Route::get('users', [UserAccountController::class, 'getUsers']);

    Route::put('user-edit/{uuid}', [UserAccountController::class, 'editUser']);

    Route::delete('user-delete/{uuid}', [UserAccountController::class, 'deleteUser']);

    Route::post('create', [UserAccountController::class, 'createAdmin']);

    Route::get('logout', [AdminAuthController::class, 'logout']);

});
