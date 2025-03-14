<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentStoreRequest extends BaseRequest
{
    protected int $postId;
    protected string $author;
    protected string $content;
    protected string $password;
    protected int $userId;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
