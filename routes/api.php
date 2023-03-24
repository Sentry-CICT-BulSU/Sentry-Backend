<?php

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware(['auth:api', 'client'])->group(function () {

    Route::get('/user', function (Request $request) {
        $request->user()->accessToken;
        return [$request->user(), $request->user()->accessToken];
    });

    Route::post('/token/revoke', function (Request $request) {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'User logged out successfully'], 200);
    });

    Route::get('/redirect', function (Request $request) {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => 2,
            'redirect_uri' => config('app.url') . '/api/callback',
            'response_type' => 'code',
            'scope' => '*',
            'state' => $state,
            // 'prompt' => '', // "none", "consent", or "login"
        ]);

        return redirect(config('app.url') . '/oauth/authorize?' . $query);
    });

    Route::get('/callback', function (Request $request) {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class,
            'Invalid state value.'
        );

        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'redirect_uri' => config('app.front_url') . '/callback',
            'code' => $request->code,
        ]);

        return $response->json();
    });
});
