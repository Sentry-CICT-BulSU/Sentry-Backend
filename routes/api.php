<?php

use App\Http\Controllers\Api\{
    AdminController,
};
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

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/user/types', fn(Request $request) => [
        // 'types' => $request->user()::TYPES,
        'cast' => $request->user()::TYPE_CAST
    ]);

    Route::resource('admin/users', AdminController::class)->except(['index', 'edit']);
    Route::post('admin/users/{user}/restore', [AdminController::class, 'restore']);
});
