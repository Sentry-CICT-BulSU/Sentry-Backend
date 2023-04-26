<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        // dump($request);
        // dd($request->toArray());
        return redirect()->intended();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(
        Request $request, TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ): RedirectResponse|JsonResponse {
        foreach ($request->user()->tokens->pluck('id') as $tokenId) {
            $this->revokeTokens($tokenId, $tokenRepository, $refreshTokenRepository);
        }
        Auth::guard('web')->logout();
        $this->revokeSession($request);
        return $request->redirect ?
            redirect(config('app.frontend_url'))
            : redirect('/');
    }

    public function destroyApi(
        Request $request, TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        foreach ($request->user()->tokens->pluck('id') as $tokenId) {
            $this->revokeTokens($tokenId, $tokenRepository, $refreshTokenRepository);
        }
        $token = Auth::user()->token();
        if ($token) {
            $token->revoke();
        }
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    private function revokeSession(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    private function revokeTokens(
        $tokenId,
        TokenRepository $tokenRepository,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        // // Revoke an access token...
        $tokenRepository->revokeAccessToken($tokenId);

        // // Revoke all of the token's refresh tokens...
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}
