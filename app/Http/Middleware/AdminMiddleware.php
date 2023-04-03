<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $admin = $request->user()::ADMIN;
        $types = $request->user()::TYPES;
        if ($request->user()->type !== $types[$admin]) {
            return response()->json([
                'message' => 'You are not authorized to access this resource'
            ], 403);
        }
        return $next($request);
    }
}
