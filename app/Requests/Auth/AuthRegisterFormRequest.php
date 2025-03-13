<?php

namespace App\Requests\Auth;

use App\Requests\BaseFormRequest;

class AuthRegisterFormRequest extends BaseFormRequest
{
    /**
     * 요청을 인증할지 여부
     */
    public function authorize(): bool
    {
        return true; // 권한 체크를 추가할 수도 있음
    }

    /**
     * 유효성 검사 규칙
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|max:32',
            'email' => 'required|email|max:64|unique:users,email',
            'name' => 'required|string|min:1|max:255',
        ];
    }
}
