<?php

namespace App\Requests;

class PostStoreRequest extends BaseRequest
{
    protected string $title;
    protected string $content;
    protected string $author;
}
