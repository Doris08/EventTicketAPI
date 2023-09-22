<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateRequest;
use App\Http\Requests\Users\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected User $user)
    {

    }

    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function register(CreateRequest $request)
    {
        try {
            $this->user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'code' => 201,
                'message' => 'User created successfully',
                'token' => $this->user->createToken("auth_token")->plainTextToken,
                'token_type' => 'Bearer',
                'data' => $this->user
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Something went wrong. User could not be created'
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'Email or Password are not valid.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'Something went wrong. User could not be logged in'
            ], 500);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'User logged out successfully',
        ], 200);
    }
}
