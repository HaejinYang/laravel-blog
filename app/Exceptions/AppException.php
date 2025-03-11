<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
