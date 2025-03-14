<?php

namespace App\Requests\Comment;

use App\Requests\BaseFormRequest;

class CommentUpdateFormRequest extends BaseFormRequest
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
            'content' => 'required|string|min:1',
            'password' => 'required|string|min:4',
        ];
    }

    public function validated($key = null, $default = null)
    {
        // $this->user는 미들웨어 auth:sanctum에 의하여 채워짐
        return array_merge(parent::validated($key, $default), ['userId' => $this->user()->id]);
    }
}
