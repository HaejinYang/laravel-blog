<?php

namespace App\Requests;

use App\Exceptions\InternelServerError;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * FormRequest 클래스에 대응하는 Request 클래스로 변환한다.
     */
    public function toRequest()
    {
        $dtoClass = $this->detectRequestClass();
        if (!class_exists($dtoClass)) {
            Log::error("Request 클래스를 찾을 수 없습니다. :{$dtoClass}");
            throw new InternelServerError();
        }

        return $dtoClass::fromArray($this);
    }

    /**
     * FormRequest 클래스명으로 Request 클래스명을 유추한다.
     */
    protected function detectRequestClass(): string
    {
        // 현재 FormRequest 클래스명 가져오기
        $formRequestClass = static::class;

        // "FormRequest"를 "Request"로 변경하여 DTO 클래스명 유추
        if (str_ends_with($formRequestClass, 'FormRequest')) {
            return str_replace('FormRequest', 'Request', $formRequestClass);
        }

        Log::error("올바른 FormRequest, Request 클래스 관계가 아닙니다. :{$formRequestClass}");
        throw new InternelServerError();
    }
}
