<?php

namespace App\Responses;

use App\Models\Post;
use JsonSerializable;

class PostResponse implements JsonSerializable
{
    private string $title;
    private string $content;
    private string $author;

    public function __construct(Post $post)
    {
        $this->title = $post->title;
        $this->content = $post->content;
        $this->author = $post->author;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
        ];
    }
}
