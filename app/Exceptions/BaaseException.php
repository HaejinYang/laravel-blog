<?php

namespace App\Exceptions;

use Exception;

/**
 * 애플리케이션 예외의 최상위 클래스. 모든 애플리케이션 예외는 BaseException을 상속해야 함.
 */
class BaaseException extends Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
