<?php

namespace App\Exceptions;

class PostNotFound extends BaaseException
{
    public function __construct()
    {
        parent::__construct('존재하지 않는 글입니다.', 404);
    }
}
