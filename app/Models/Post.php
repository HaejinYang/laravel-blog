<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 포스트(게시글) 모델 클래스
 *
 * @property string $title 포스트 제목
 * @property string $content 포스트 내용
 * @property string $author 포스트 작성자
 */
class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author',
    ];
    private string $title;
    private string $content;
    private string $author;
}
