<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentStoreRequest extends BaseRequest
{
    protected int $post_id;
    protected string $author;
    protected string $content;
    protected string $password;

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->post_id;
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
