<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckTokenExpiration
{
    public function handle($request, Closure $next)
    {
        // Skip middleware for login route
        if ($request->is('api/login')) {
            return $next($request);
        }

        $user = Auth::guard('api')->user();

        if (!$user || !$user->api_token) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated. No token provided.',
                'msg' => 'Unauthenticated. No token provided.'
            ], 401);
        }

        if ($user->token_expires_at && Carbon::now()->gt($user->token_expires_at)) {
            // Invalidate expired token
            $user->api_token = null;
            $user->token_expires_at = null;
            $user->save();

            return response()->json([
                'status' => 401,
                'message' => 'Session expired. Please login again.',
                'msg' => 'Session expired. Please login again.',
                'expired_at' => $user->token_expires_at
            ], 401);
        }

        // Optionally: Refresh token expiration on each request
        // $user->token_expires_at = Carbon::now()->addMinutes(2);
        // $user->save();

        return $next($request);
    }
}