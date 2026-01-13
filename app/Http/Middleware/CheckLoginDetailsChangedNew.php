<?php

namespace App\Http\Middleware;

use Closure;

class CheckLoginDetailsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Skip middleware for login routes and guest users
        if ($request->is('api/login') || $request->is('login') || !\Illuminate\Support\Facades\Auth::check()) {
            return $next($request);
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        
        // For API authentication
        if (\Illuminate\Support\Facades\Auth::guard('api')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('api')->user();
            return $this->checkApiUserDetails($request, $next, $user);
        }
        
        // For web authentication (admin guard)
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
            return $this->checkWebUserDetails($request, $next, $user);
        }

        return $next($request);
    }

    /**
     * Check API user details for changes
     */
    private function checkApiUserDetails($request, Closure $next, $user)
    {
        if (!$user) {
            return \Illuminate\Support\Facades\Response::json([
                'status' => 401,
                'message' => 'User not found. Please login again.',
                'msg' => 'User not found. Please login again.',
                'logout' => true
            ], 401);
        }

        // Store user details hash in session on first request
        $sessionKey = 'user_details_hash_' . $user->id;
        $currentDetailsHash = $this->generateUserDetailsHash($user);
        
        $storedHash = \Illuminate\Support\Facades\Session::get($sessionKey);
        
        if ($storedHash === null) {
            // First request, store the hash
            \Illuminate\Support\Facades\Session::put($sessionKey, $currentDetailsHash);
        } elseif ($storedHash !== $currentDetailsHash) {
            // User details have changed, force logout
            $this->logoutUser('api');
            \Illuminate\Support\Facades\Session::forget($sessionKey);
            
            return \Illuminate\Support\Facades\Response::json([
                'status' => 401,
                'message' => 'Account details have been modified. Please login again for security.',
                'msg' => 'Account details have been modified. Please login again for security.',
                'logout' => true,
                'reason' => 'details_changed'
            ], 401);
        }

        return $next($request);
    }

    /**
     * Check web user details for changes
     */
    private function checkWebUserDetails($request, Closure $next, $user)
    {
        if (!$user) {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
            if ($request->expectsJson()) {
                return \Illuminate\Support\Facades\Response::json([
                    'status' => 401,
                    'message' => 'User not found. Please login again.',
                    'logout' => true
                ], 401);
            }
            return \Illuminate\Support\Facades\Redirect::route('admin.login')->with('error', 'User not found. Please login again.');
        }

        // Store user details hash in session on first request
        $sessionKey = 'user_details_hash_' . $user->id;
        $currentDetailsHash = $this->generateUserDetailsHash($user);
        
        $storedHash = \Illuminate\Support\Facades\Session::get($sessionKey);
        
        if ($storedHash === null) {
            // First request, store the hash
            \Illuminate\Support\Facades\Session::put($sessionKey, $currentDetailsHash);
        } elseif ($storedHash !== $currentDetailsHash) {
            // User details have changed, force logout
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
            \Illuminate\Support\Facades\Session::forget($sessionKey);
            \Illuminate\Support\Facades\Session::flush(); // Clear all session data
            
            if ($request->expectsJson()) {
                return \Illuminate\Support\Facades\Response::json([
                    'status' => 401,
                    'message' => 'Account details have been modified. Please login again for security.',
                    'logout' => true,
                    'reason' => 'details_changed'
                ], 401);
            }
            
            return \Illuminate\Support\Facades\Redirect::route('admin.login')->with('error', 'Account details have been modified. Please login again for security.');
        }

        return $next($request);
    }

    /**
     * Generate a hash of important user details
     */
    private function generateUserDetailsHash($user)
    {
        $details = [
            'id' => $user->id,
            'email' => $user->email,
            'password' => $user->password, // This will detect password changes
            'name' => $user->name,
            'updated_at' => $user->updated_at ? $user->updated_at->timestamp : null,
        ];

        // Add any additional fields you want to monitor
        if (isset($user->status)) {
            $details['status'] = $user->status;
        }
        
        if (isset($user->is_active)) {
            $details['is_active'] = $user->is_active;
        }

        if (isset($user->role)) {
            $details['role'] = $user->role;
        }

        return hash('sha256', serialize($details));
    }

    /**
     * Logout user from specified guard
     */
    private function logoutUser($guard = null)
    {
        if ($guard === 'api') {
            $user = \Illuminate\Support\Facades\Auth::guard('api')->user();
            if ($user) {
                // Clear API token
                $user->api_token = null;
                $user->token_expires_at = null;
                $user->save();
            }
            \Illuminate\Support\Facades\Auth::guard('api')->logout();
        } else {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
        }
    }
}
