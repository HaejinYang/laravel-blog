<?php

namespace App\Requests\Auth;

use App\Requests\BaseRequest;

class AuthRegisterRequest extends BaseRequest
{
    protected string $password;
    protected string $email;
    protected string $name;
}
