<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\auth\LoginRequest;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        try {

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('auth-token');
    
                return response()->json([
                    "status" => 'success',
                    "message" => 'Login successful.',
                    "data" => [
                        'user' => $user,
                        'auth_token' => $token->plainTextToken
                    ]
                ], 200);
            } else {

                return response()->json([
                    "status" => 'error',
                    "message" => 'Invalid credentials.',
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => 'error',
                "message" => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
