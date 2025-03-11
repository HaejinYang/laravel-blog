<?php

namespace App\Responses;

use App\Models\Post;

class PostResponse extends BaseResponse
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
}
