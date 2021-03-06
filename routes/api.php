<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**********************************************************************************************************************
 *                                                 Private Routes                                                     *
 **********************************************************************************************************************/


/**********************************************************************************************************************
 *                                                Protected Routes                                                    *
 **********************************************************************************************************************/

Route::resource('events', EventController::class)->middleware('auth:sanctum');

/**********************************************************************************************************************
 *                                                Public Routes                                                       *
 **********************************************************************************************************************/


/**********************************************************************************************************************
 *                                                 Auth Routes                                                        *
 **********************************************************************************************************************/
Route::controller(OAuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('/user', 'user')->name('user');
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'callback')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});
