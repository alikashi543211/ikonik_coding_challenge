<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\User\ConnectionController;
use App\Http\Controllers\Api\User\ConnectionRequestController;
use Illuminate\Http\Request;
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
Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
    });
    Route::controller(RegisterController::class)->group(function () {
        Route::post('register', 'register');
    });
});
Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('auth/logout', 'logout');
    });
    Route::prefix('connection_request')->group(function () {
        Route::controller(ConnectionRequestController::class)->group(function () {
            Route::get('suggestionList', 'suggestionList');
            Route::get('sentRequestList', 'sentRequestList');
            Route::get('receivedRequestList', 'receivedRequestList');
            Route::get('connectionList', 'connectionList');
            Route::post('sendConnection', 'sendConnection');
            Route::post('withdrawSentRequest', 'withdrawSentRequest');
            Route::post('removeConnection', 'removeConnection');
            Route::post('acceptConnection', 'acceptConnection');
        });
    });
});
