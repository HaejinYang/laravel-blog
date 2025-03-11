<?php

namespace App\Responses;

use JsonSerializable;

class ErrorResponse implements JsonSerializable
{
    private int $code;
    private string $message;

    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}
