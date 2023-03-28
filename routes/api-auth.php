<?php
use App\Http\Controllers\Auth\AuthenticationController;



Route::controller(AuthenticationController::class)->group(function () {
    // Route::post('login', 'login')->name('login.store');
    // Route::get('login', 'view')->name('login');
    // Route::post('register', 'register')->name('register');
});

Route::middleware(['auth:api'])->group(function () {

    Route::post('/token/revoke', function (Request $request) {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'User logged out successfully'], 200);
    });
});
