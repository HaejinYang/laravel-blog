<?php

namespace App\Services;

use App\Exceptions\CommentNotFound;
use App\Models\Comment;
use App\Responses\CommentResponse;

class CommentService
{
    /**
     * @param int $commentId 댓글ID
     * @return CommentResponse
     */
    public function getOne(int $commentId): CommentResponse
    {
        $comment = Comment::findOr($commentId, fn() => throw new CommentNotFound());
        $response = new CommentResponse($comment);

        return $response;
    }
}
