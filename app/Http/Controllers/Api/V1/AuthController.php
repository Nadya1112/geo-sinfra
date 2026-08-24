<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use App\Http\Resources\Api\V1\UserResource;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Email atau kata sandi salah', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 'Login berhasil');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Berhasil logout');
    }

    public function profile(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()), 
            'Profil pengguna'
        );
    }
}
