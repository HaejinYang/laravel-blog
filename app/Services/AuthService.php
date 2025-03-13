<?php

namespace App\Services;

use App\Exceptions\InvalidLogin;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Requests\Auth\AuthLoginRequest;
use App\Requests\Auth\AuthRegisterRequest;
use App\Responses\AuthLoginResponse;
use App\Responses\AuthRegisterResponse;

class AuthService
{
    public function register(AuthRegisterRequest $request): AuthRegisterResponse
    {
        $user = User::create($request->toArray());
        $response = new AuthRegisterResponse($user);

        return $response;
    }

    public function login(AuthLoginRequest $request): AuthLoginResponse
    {
        $user = User::where('email', $request->getEmail())->firstOr(function () {
            throw new UserNotFound();
        });

        $isValidated = $user->validatePassword($request->getPassword());
        if (!$isValidated) {
            throw new InvalidLogin();
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $response = new AuthLoginResponse($user, $token);

        return $response;
    }
}
