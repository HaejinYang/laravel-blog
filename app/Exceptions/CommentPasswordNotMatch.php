<?php

namespace App\Exceptions;

class CommentPasswordNotMatch extends BaaseException
{
    public function __construct()
    {
        parent::__construct('댓글의 비밀번호와 다릅니다.', 401);
    }
}
