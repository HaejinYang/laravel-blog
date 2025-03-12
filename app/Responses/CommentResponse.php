<?php

namespace App\Responses;

use App\Models\Comment;

class CommentResponse extends BaseResponse
{
    private int $id;
    private string $content;
    private string $author;

    public function __construct(Comment $comment)
    {
        $this->id = $comment->id;
        $this->content = $comment->content;
        $this->author = $comment->author;
    }
}
