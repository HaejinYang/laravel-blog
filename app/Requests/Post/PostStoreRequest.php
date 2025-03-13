<?php

namespace App\Requests\Post;

use App\Requests\BaseRequest;

class PostStoreRequest extends BaseRequest
{
    protected string $title;
    protected string $content;
    protected string $author;
    protected int $userId;
}
