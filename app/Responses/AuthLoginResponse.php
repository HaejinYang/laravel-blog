<?php

namespace App\Responses;

use App\Models\User;

class AuthLoginResponse extends BaseResponse
{
    private int $id;
    private string $email;
    private string $token;

    public function __construct(User $user, string $token)
    {
        $this->id = $user->id;
        $this->email = $user->email;
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
