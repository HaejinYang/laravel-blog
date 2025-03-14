<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentDeleteRequest extends BaseRequest
{
    protected string $password;
    protected int $userId;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

}
