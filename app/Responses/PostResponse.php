<?php

namespace App\Responses;

use App\Models\Post;

class PostResponse extends BaseResponse
{
    private int $id;
    private string $title;
    private string $content;
    private string $author;
    private int $userId;

    public function __construct(Post $post)
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->author = $post->author;
        $this->userId = $post->user_id;
    }

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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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
    public function getAuthor(): string
    {
        return $this->author;
    }
}
