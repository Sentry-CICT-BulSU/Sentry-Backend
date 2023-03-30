<?php

use App\Http\Controllers\Api\{
    AdminController,
    SemestersController
};
use App\Http\Controllers\Api\SectionsController;
use App\Http\Controllers\Api\SubjectsController;
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

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::post('users/{user}/restore', 'restore')->name('users.restore');
            Route::resource('users', AdminController::class)->except(['index', 'edit']);
        });

        Route::controller(SemestersController::class)->group(function () {
            Route::post('semesters/{semester}/restore', 'restore')->name('semesters.restore');
            Route::resource('semesters', SemestersController::class)->except(['edit']);
        });

        Route::controller(SectionsController::class)->group(function () {
            Route::post('sections/{section}/restore', 'restore')->name('sections.restore');
            Route::resource('sections', SectionsController::class)->except(['edit']);
        });

        Route::controller(SubjectsController::class)->group(function () {
            Route::post('subjects/{section}/restore', 'restore')->name('subjects.restore');
            Route::resource('subjects', SubjectsController::class)->except(['edit']);
        });
    });
});
