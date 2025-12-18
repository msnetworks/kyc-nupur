<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckLoginDetailsChanged
{
    public function handle($request, Closure $next)
    {
        // Skip middleware for login routes and guest users
        if ($request->is('api/login') || $request->is('login') || $request->is('admin/login')) {
            return $next($request);
        }

        // Check API authentication
        if ($request->is('api/*')) {
            return $this->checkApiUser($request, $next);
        }

        // Check web authentication (admin guard)
        if (Auth::guard('admin')->check()) {
            return $this->checkWebUser($request, $next);
        }

        return $next($request);
    }

    private function checkApiUser($request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'User not found. Please login again.',
                'logout' => true
            ], 401);
        }

        $sessionKey = 'api_user_hash_' . $user->id;
        $currentHash = $this->generateUserHash($user);
        $storedHash = Session::get($sessionKey);
        
        if ($storedHash === null) {
            Session::put($sessionKey, $currentHash);
        } elseif ($storedHash !== $currentHash) {
            // User details changed, logout
            if ($user->api_token) {
                $user->api_token = null;
                $user->token_expires_at = null;
                $user->save();
            }
            Auth::guard('api')->logout();
            Session::forget($sessionKey);
            
            return response()->json([
                'status' => 401,
                'message' => 'Account details have been modified. Please login again for security.',
                'logout' => true,
                'reason' => 'details_changed'
            ], 401);
        }

        return $next($request);
    }

    private function checkWebUser($request, Closure $next)
    {
        $user = Auth::guard('admin')->user();
        
        if (!$user) {
            Auth::guard('admin')->logout();
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'User not found. Please login again.',
                    'logout' => true
                ], 401);
            }
            return redirect()->route('admin.login')->with('error', 'User not found. Please login again.');
        }

        $sessionKey = 'admin_user_hash_' . $user->id;
        $currentHash = $this->generateUserHash($user);
        $storedHash = Session::get($sessionKey);
        
        if ($storedHash === null) {
            Session::put($sessionKey, $currentHash);
        } elseif ($storedHash !== $currentHash) {
            // User details changed, logout
            Auth::guard('admin')->logout();
            Session::forget($sessionKey);
            Session::flush();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Account details have been modified. Please login again for security.',
                    'logout' => true,
                    'reason' => 'details_changed'
                ], 401);
            }
            
            return redirect()->route('admin.login')->with('error', 'Account details have been modified. Please login again for security.');
        }

        return $next($request);
    }

    private function generateUserHash($user)
    {
        $details = [
            'id' => $user->id,
            'email' => $user->email,
            'password' => $user->password,
            'name' => $user->name,
            'updated_at' => $user->updated_at ? $user->updated_at->timestamp : null,
        ];

        // Monitor additional fields if they exist
        if (isset($user->status)) {
            $details['status'] = $user->status;
        }
        
        if (isset($user->is_active)) {
            $details['is_active'] = $user->is_active;
        }

        return hash('sha256', serialize($details));
    }
}
