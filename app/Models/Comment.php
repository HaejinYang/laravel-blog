<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author',
        'password',
        'content',
        'postId'
    ];

    private int $postId;
    private string $author;
    private string $password;
    private string $content;
}
