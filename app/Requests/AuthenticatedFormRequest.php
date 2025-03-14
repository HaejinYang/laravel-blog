<?php

namespace App\Requests;

class AuthenticatedFormRequest extends BaseFormRequest
{
    public function validated($key = null, $default = null)
    {
        // $this->user는 미들웨어 auth:sanctum에 의하여 채워짐
        return array_merge(parent::validated($key, $default), ['userId' => $this->user()->id]);
    }
}
