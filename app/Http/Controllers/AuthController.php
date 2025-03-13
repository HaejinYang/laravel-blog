<?php

namespace App\Http\Controllers;

use App\Requests\Auth\AuthLoginFormRequest;
use App\Requests\Auth\AuthRegisterFormRequest;
use App\Responses\AuthLoginResponse;
use App\Responses\AuthRegisterResponse;
use App\Responses\UserResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * 회원가입
     */
    public function register(AuthRegisterFormRequest $formRequest): AuthRegisterResponse
    {
        $request = $formRequest->toRequest();
        $response = $this->authService->register($request);

        return $response;
    }

    /**
     * 로그인 및 API 토큰 발급
     */
    public function login(AuthLoginFormRequest $formRequest): AuthLoginResponse
    {
        $request = $formRequest->toRequest();
        $response = $this->authService->login($request);

        return $response;
    }

    /**
     * 로그아웃 (토큰 삭제)
     */
    public function logout(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();

        // 응답없음
    }

    /**
     * 현재 로그인한 유저 정보 반환
     */
    public function me(Request $request): UserResponse
    {
        $response = new UserResponse($request->user());

        return $response;
    }
}

