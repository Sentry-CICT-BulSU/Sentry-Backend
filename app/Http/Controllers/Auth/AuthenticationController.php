<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthenticationController
{

    public function token(Request $request)
    {
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => config('auth.passport.client_id'),
            'client_secret' => config('auth.passport.client_secret'),
        ]);

        $proxy = Request::create('oauth/token', 'post');

        return Route::dispatch($proxy);
    }
    public function view()
    {
        return view('auth.login');
    }
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $token = $request->user()->createToken('laravel_token')->accessToken;

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        // return response()->json([
        //     'message' => 'You are logged in',
        //     'user' => $request->user(),
        //     'token' => $token,
        //     'laravel_token' => $request->user()->laravel_token
        // ]);
        return redirect()->intended();
    }

    public function logout(Request $request)
    {

    }

    public function refresh(Request $request)
    {

    }

    public function register(RegisterRequest $request)
    {
        $user = $request->saveNewUser();

        return response()->json([
            'message' => 'You are registered',
            'token' => $user->createToken('access_token')->accessToken,
        ]);
    }
}
