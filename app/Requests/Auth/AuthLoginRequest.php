<?php

namespace App\Requests\Auth;

use App\Requests\BaseRequest;

class AuthLoginRequest extends BaseRequest
{
    protected string $password;
    protected string $email;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
