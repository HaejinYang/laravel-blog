<?php

namespace App\Exceptions;

class CommentNotFound extends BaaseException
{
    public function __construct()
    {
        parent::__construct('존재하지 않는 댓글입니다.', 404);
    }
}
