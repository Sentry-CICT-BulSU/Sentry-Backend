<?php
use App\Http\Controllers\Auth\AuthenticationController;



Route::controller(AuthenticationController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
});
