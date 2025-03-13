<?php

namespace App\Exceptions;

class InvalidLogin extends BaaseException
{
    public function __construct()
    {
        parent::__construct('로그인을 실패했습니다. 계정, 비밀번호를 확인해주세요.', 401);
    }
}
