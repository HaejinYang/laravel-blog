<?php

namespace App\Exceptions;

class NotCommentOwner extends BaaseException
{
    public function __construct()
    {
        parent::__construct('댓글의 소유자가 아닙니다.', 403);
    }
}
