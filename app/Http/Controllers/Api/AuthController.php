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
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticates a user and returns an access token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object", 
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 ),
     *                 @OA\Property(property="auth_token", type="string", example="1|abcdef123456...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred.")
     *         )
     *     )
     * )
     */
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
                return unauthorized('Invalid credentials.');
            }
        } catch (\Exception $e) {
         
            return error($e->getMessage(), 500);
        }
    }
    
/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     description="Allows a new user to register and returns a token on successful registration.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name", "email", "password", "profile_image"},
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *                 @OA\Property(property="password", type="string", format="password", example="password123"),
 *                 @OA\Property(property="profile_image", type="string", format="binary", example="profile.jpg")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                 @OA\Property(property="profile_image", type="string", example="image.png")              
 *             ),
 *             @OA\Property(property="token", type="string", example="1|qwertyuiopasdfghjklzxcvbnm1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Internal server error")
 *         )
 *     )
 * )
 */
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
    
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out a user",
     *     description="Logs out the authenticated user by deleting their tokens.",
     *     tags={"Auth"},
     *     security={
     *         {"api_key": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated.")
    *         )
    *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */

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
