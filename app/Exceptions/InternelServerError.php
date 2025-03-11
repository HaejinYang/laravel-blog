<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class InternelServerError extends BaaseException
{
    public function __construct()
    {
        parent::__construct('서버 내부 오류가 발생했습니다.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
