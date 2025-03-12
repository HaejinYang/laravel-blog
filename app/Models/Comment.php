<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author',
        'password',
        'content',
        'post_id'
    ];

    private int $post_id;
    private string $author;
    private string $password;
    private string $content;
}
