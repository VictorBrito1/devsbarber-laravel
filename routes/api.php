<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::group(['prefix' => 'users'], function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/me', [UserController::class, 'read']);
    Route::put('/me', [UserController::class, 'update']);
    Route::post('/avatar', [UserController::class, 'updateAvatar']);

    Route::get('/favorites', [UserController::class, 'favorites']);
    Route::post('/favorite', [UserController::class, 'favorite']);

    Route::get('/appointments', [UserController::class, 'getAppointments']);
});

Route::group(['prefix' => 'barbers'], function () {
    Route::get('/search', [BarberController::class, 'search']);

    Route::get('/', [BarberController::class, 'list']);
    Route::get('/{id}', [BarberController::class, 'read']);
    Route::post('/{id}/appointment', [BarberController::class, 'setAppointment']);
});
