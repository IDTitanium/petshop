<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\UserAuthController;
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
