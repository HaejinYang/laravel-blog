<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentUpdateRequest extends BaseRequest
{
    protected string $content;
    protected string $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
