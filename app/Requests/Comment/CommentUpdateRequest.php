<?php

namespace App\Requests\Comment;

use App\Requests\BaseRequest;

class CommentUpdateRequest extends BaseRequest
{
    protected string $content;
}
