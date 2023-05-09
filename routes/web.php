<?php

use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth' /* , 'verified' */])->group(function () {
    Route::get('time-now', fn() => [
        'now' => now(),
        'to string' => now()->toString(),
        'to Date string' => now()->toDateString(),
        'to Time string' => now()->toTimeString(),
        'to DateTime string' => now()->toDateTimeString(),
    ]);
    Route::controller(UsersController::class)->group(function () {
        // Route::get('user', fn(Request $request) => $request->user());
        // Route::post('user', 'update');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/test')->middleware(['admin'])->group(function () {
        Route::get('/pdf-view', [ReportsController::class, 'pdf']);
        Route::get('/view', [ReportsController::class, 'view']);
    });

    Route::get('/', function () {
        return match (Auth::user()->type) {
            // User::TYPES[USER::ADMIN] => redirect('/telescope'),
            // default => 'Larvel: ' . Application::VERSION,
            default => redirect(config('app.frontend_url')),
        };
        // return redirect(config('app.frontend_url'));
    });
});

Route::get('/ping', fn() => response()->json('success', 200));
require __DIR__ . '/auth.php';
