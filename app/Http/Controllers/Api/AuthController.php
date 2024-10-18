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

    public function register(RegisterRequest $request)
    {
        try {
            $request->password = Hash::make($request->password);
           
           
            $user = User::createUser($request);
    
          
            $token = $user->createToken('auth-token')->plainTextToken;
            //   Mail::to('shahshan@nextgeni.com')
            // ->cc(['shahshan871@gmail.com'])
            // ->send(new RegisterationMail($user));
     
            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully',
                'user' => $user,
                'token' => $token
            ], 201); 
    
        } catch (\Exception $e) {
    
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500); 
        }
    }

    public function logout()
{
    try {
        
        $user=Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ], 200); 

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Could not logout, something went wrong. Please try again later.',
        ], 500); 
    }
}
    
}
