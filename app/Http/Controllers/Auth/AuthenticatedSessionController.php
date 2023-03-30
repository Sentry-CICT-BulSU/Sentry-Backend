<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        return redirect()->intended();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(
        Request $request,
        TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ): RedirectResponse {
        foreach (Auth::guard('web')->user()->tokens->pluck('id') as $tokenId) {
            // // Revoke an access token...
            $tokenRepository->revokeAccessToken($tokenId);

            // // Revoke all of the token's refresh tokens...
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
