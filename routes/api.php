<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'v1/user', 'middleware' => 'auth:api'], function (): void {
    Route::get('orders', [UserController::class, 'listUserOrders']);
});

/**
 * Admin Routes
 */
Route::post('/v1/admin/login', [AdminAuthController::class, 'login']);

Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth:api', 'admin']], function (): void {
    Route::get('users', [UserAccountController::class, 'getUsers']);

    Route::put('user-edit/{uuid}', [UserAccountController::class, 'editUser']);

    Route::delete('user-delete/{uuid}', [UserAccountController::class, 'deleteUser']);

    Route::post('create', [UserAccountController::class, 'createAdmin']);

});
