<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentDeleteRequest extends BaseRequest
{
    protected string $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}
