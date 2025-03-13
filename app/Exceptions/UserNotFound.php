<?php

namespace App\Exceptions;

class UserNotFound extends BaaseException
{
    public function __construct()
    {
        parent::__construct('존재하지 않는 계정입니다.', 404);
    }
}
