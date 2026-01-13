<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str; // Add this import
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();
        $jsonData = json_encode($data);

        // Insert the JSON data into the 'api_request' table
        DB::table('api_request')->insert(['request_data' => $jsonData]);
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException();
        }

        $user = Auth::user();
        $token = Str::random(60); // Fixed: Using Laravel's Str helper

        $user->api_token = $token;
        // $user->token_expires_at = now()->addMinutes(1);
        $user->token_expires_at = now()->addHours(10)->toDateTimeString(); // Set 8-hour expiration (480 minutes)
        $user->save();

        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'msg' => "Login successful",
            'token_type' => 'Bearer',
            'user_id' => $user->id,
            'expires_in' => 36000 // seconds
        ], 200);
    }


    // public function logout(Request $request)
    // {
    //     // Revoke the user's API token
    //     $user = Auth::user();
    //     $user->api_token = null;
    //     $user->save();

    //     return response()->json(['message' => 'Logged out successfully']);
    // }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->token_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
