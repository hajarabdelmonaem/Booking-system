<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    private UserAuthService $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $result = $this->userAuthService->register($request->validated());
        $user = $result['user'];

        return UserResource::make($user)
            ->additional(['token' => $result['token']])
            ->response()
            ->setStatusCode(200);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $result = $this->userAuthService->login($request->validated());
        $user = $result['user'];
        
        return UserResource::make($user)
            ->additional(['token' => $result['token']])
            ->response()
            ->setStatusCode(200);
    }

    public function logout(): JsonResponse
    {
        $this->userAuthService->logout();
        return response()->json(['message' => 'Logged out successfully'],200);
    }
}
