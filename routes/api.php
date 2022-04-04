<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['controller' => AuthController::class], function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'game', 'controller' => GameController::class], function () {
        Route::get('', 'index');
        Route::get('fields', 'getFields');
        Route::post('play', 'play');
    });

    Route::group(['prefix' => 'user', 'controller' => UserController::class], function () {
        Route::get('history', 'history');
    });
});
