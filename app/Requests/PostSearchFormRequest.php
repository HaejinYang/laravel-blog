<?php

namespace App\Requests;

class PostSearchFormRequest extends BaseFormRequest
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
            'page' => 'nullable|integer|min:1',
            'pageSize' => 'nullable|integer|min:1',
            'orderBy' => 'nullable|string|in:asc,desc'
        ];
    }
}
