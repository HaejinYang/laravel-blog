<?php

namespace App\Responses;

use App\Models\Post;

class PostResponse extends BaseResponse
{
    private int $id;
    private string $title;
    private string $content;
    private string $author;

    public function __construct(Post $post)
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->author = $post->author;
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
