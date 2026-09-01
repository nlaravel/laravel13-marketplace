<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\Auth\AuthResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

public function register(RegisterRequest $request): JsonResponse
{
    $result = $this->authService->register(
        $request->string('name')->toString(),
        $request->string('email')->toString(),
        $request->string('password')->toString(),
        );

    return response()->json([
        'message' => 'Registration successful.',
        'data' => [
            'user' => new AuthResource($result['user']),
            'token' => $result['token'],
        ],
    ], 201);
}

public function login(LoginRequest $request): JsonResponse
{
    $result = $this->authService->login(
        $request->string('email')->toString(),
        $request->string('password')->toString(),
        );

    return response()->json([
        'message' => 'Login successful.',
        'data' => [
            'user' => new AuthResource($result['user']),
            'token' => $result['token'],
        ],
    ]);
}

public function me(Request $request): AuthResource
{
    return new AuthResource($request->user());
}

public function logout(Request $request): JsonResponse
{
    $this->authService->logout($request->user());

    return response()->json([
        'message' => 'Logout successful.',
    ]);
}

public function forgotPassword(
    ForgotPasswordRequest $request
): JsonResponse {
    $message = $this->authService->sendResetLink(
        $request->string('email')->toString()
    );

    return response()->json([
        'message' => $message,
    ]);
}

public function resetPassword(
    ResetPasswordRequest $request
): JsonResponse {
    $this->authService->resetPassword(
        $request->string('email')->toString(),
        $request->string('password')->toString(),
        $request->string('token')->toString(),
    );

    return response()->json([
        'message' => 'Password reset successfully.',
    ]);
}
}