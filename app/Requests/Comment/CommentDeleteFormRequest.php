<?php

namespace App\Requests\Comment;

use App\Requests\AuthenticatedFormRequest;

class CommentDeleteFormRequest extends AuthenticatedFormRequest
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
            'password' => 'nullable|string|min:4',
        ];
    }
}
