<?php

use App\Http\Controllers\Api\{
    AdminController,
    AttendanceController,
    ListsController,
    SchedulesController,
    SectionsController,
    SemestersController,
    SubjectsController,
    RoomKeyLogsController,
    RoomKeysController,
    RoomsController,
};
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\UsersController;
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

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Headers: *');
Route::get('time-now', fn() => now());
Route::middleware(['auth:api'])->group(function () {
    Route::controller(UsersController::class)->group(function () {
        Route::get('user', fn(Request $request) => $request->user());
        // Route::resource('user', UsersController::class)->only('update');
        Route::post('user', 'update');
    });
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendances/stats', 'statistics')->name('users.stats');
        Route::get('attendances/user/{user}', 'attendances')->name('users.attendances');
        Route::resource('attendances', AttendanceController::class)->only(['index', 'show']);
        Route::resource('schedules.attendances', AttendanceController::class)->only(['store']);
    });
    Route::controller(RoomsController::class)->group(function () {
        Route::resource('rooms', RoomsController::class)->only(['show']);
    });
    Route::controller(RoomKeyLogsController::class)->group(function () {
        // Route::post('keys/{key}/logs/{log}/restore', 'restore')->name('keys.logs.restore');
        Route::get('logs', 'index')->name('key.logs.index');
        Route::get('logs/available', 'availableKeys')->name('key.logs.available-keys');
        // Route::get('keys/{key}/logs', 'show')->name('key.logs.show');
    });
    Route::resource('schedules', SchedulesController::class)->only(['index', 'show']);
    Route::resource('keys', RoomKeysController::class)->only(['index']);

    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('list', ListsController::class);

        Route::controller(ReportsController::class)->group(function () {
            Route::get('reports/csv', 'csv')->name('reports');
            Route::get('reports/pdf', 'pdf')->name('pdf');
            Route::get('reports/view', 'view')->name('view');
        });
        Route::controller(AdminController::class)->group(function () {
            Route::patch('reset-password', 'resetPassword')->name('reset.password');
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
            Route::get('logs/available', 'availableKeys')->name('key.logs.available-keys');
            Route::get('keys/{key}/logs', 'show')->name('key.logs.show');
        });

        Route::controller(RoomKeysController::class)->group(function () {
            // Route::post('keys/{key}/restore', 'restore')->name('keys.restore');
            // Route::post('keys/{key}/return', 'return')->name('keys.return');
            Route::resource('keys', RoomKeysController::class)->except(['create', 'destroy', 'edit']);
        });

        Route::controller(AttendanceController::class)->group(function () {
            Route::post('attendances/{attendance}/restore', 'restore')->name('attendances.restore');
            Route::resource('attendances', AttendanceController::class)->except(['store', 'update', 'create', 'edit']);
        });
    });
});
