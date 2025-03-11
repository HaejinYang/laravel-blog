<?php

namespace App\Responses;

/**
 * 에러 응답 전용 클래스.
 * @see /bootstrap/app.php의 withExceptions
 */
class ErrorResponse extends BaseResponse
{
    private int $code;
    private string $message;

    private array $validations = [];

    public function __construct(int $code, string $message, $validations = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->validations = $validations;
    }

    public function addValidation(string $field, string $message): void
    {
        $this->validations[$field] = $message;
    }
}
