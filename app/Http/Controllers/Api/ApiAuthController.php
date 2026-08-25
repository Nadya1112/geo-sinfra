<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    use \App\Traits\ApiResponse;

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
            'user' => new \App\Http\Resources\Api\V1\UserResource($user),
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
            new \App\Http\Resources\Api\V1\UserResource($request->user()), 
            'Profil pengguna'
        );
    }
}
