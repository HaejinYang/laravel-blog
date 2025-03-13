<?php

namespace App\Requests\Post;


use App\Requests\BaseFormRequest;

class PostStoreFormRequest extends BaseFormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:100',
        ];
    }

    public function validated($key = null, $default = null)
    {
        // $this->user는 미들웨어 auth:sanctum에 의하여 채워짐
        return array_merge(parent::validated($key, $default), ['userId' => $this->user()->id]);
    }
}
