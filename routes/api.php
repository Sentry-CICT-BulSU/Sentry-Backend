<?php

use App\Http\Controllers\Api\{
    AdminController,
    SchedulesController,
    SectionsController,
    SemestersController,
    SubjectsController,
    RoomKeysController,
    RoomsController,
};
use App\Http\Controllers\Api\RoomKeyLogsController;
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
        'cast' => $request->user()::TYPES
    ]);

    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::post('users/{user}/restore', 'restore')->name('users.restore');
            Route::resource('users', AdminController::class)->except(['create', 'edit']);
        });

        Route::controller(SemestersController::class)->group(function () {
            Route::post('semesters/{semester}/restore', 'restore')->name('semesters.restore');
            Route::resource('semesters', SemestersController::class)->except(['create', 'edit']);
        });

        Route::controller(SectionsController::class)->group(function () {
            Route::post('sections/{section}/restore', 'restore')->name('sections.restore');
            Route::resource('sections', SectionsController::class)->except(['create', 'edit']);
        });

        Route::controller(SubjectsController::class)->group(function () {
            Route::post('subjects/{section}/restore', 'restore')->name('subjects.restore');
            Route::resource('subjects', SubjectsController::class)->except(['create', 'edit']);
        });

        Route::controller(RoomsController::class)->group(function () {
            Route::post('rooms/{room}/restore', 'restore')->name('rooms.restore');
            Route::resource('rooms', RoomsController::class)->except(['create', 'edit']);
        });

        Route::controller(SchedulesController::class)->group(function () {
            Route::post('schedules/{schedule}/restore', 'restore')->name('schedules.restore');
            Route::resource('schedules', SchedulesController::class)->except(['create', 'edit']);
        });

        Route::controller(RoomKeyLogsController::class)->group(function () {
            // Route::post('keys/{key}/logs/{log}/restore', 'restore')->name('keys.logs.restore');
            Route::get('logs', 'index')->name('key.logs.index');
            Route::get('keys/{key}/logs', 'show')->name('key.logs.show');
        });

        Route::controller(RoomKeysController::class)->group(function () {
            // Route::post('keys/{key}/restore', 'restore')->name('keys.restore');
            Route::resource('keys', RoomKeysController::class)->except(['create', 'destroy', 'edit']);
        });
    });
});
