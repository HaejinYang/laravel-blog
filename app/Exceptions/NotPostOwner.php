<?php

namespace App\Exceptions;

class NotPostOwner extends BaaseException
{
    public function __construct()
    {
        parent::__construct('게시글의 소유자가 아닙니다.', 403);
    }
}
