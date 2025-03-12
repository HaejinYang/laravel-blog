<?php

namespace App\Requests\Post;

use App\Requests\BaseRequest;

class PostUpdateRequest extends BaseRequest
{
    protected string $title;
    protected string $content;
}
