<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegisterationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('auth-token');
                return success([
                    'user' => $user,
                    'auth_token' => $token->plainTextToken
                ],'Login successful.', 200);
            } else {
                return error('Invalid credentials.', 401);
            }
        } catch (\Exception $e) {
         
            return error($e->getMessage(), 500);
        }
    }
    

    public function register(RegisterRequest $request)
    {
        try {
            $request->password = Hash::make($request->password);
            $user = User::createUser($request);
            $token = $user->createToken('auth-token')->plainTextToken;
    
            return success([
                'user' => $user,
                'token' => $token
            ], 'Registered successfully', 201);
        } catch (\Exception $e) {

            return error($e->getMessage(), 500);
        }
    }
    

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return success(null, 'Logged out successfully', 200);
        } catch (\Exception $e) {
    
            return error($e->getMessage(), 500);
        }
    }
    
    
}
