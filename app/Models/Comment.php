<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * postId 라는 모델 속성을
     * 내부적으로 post_id 컬럼에 읽고/쓰도록 연결
     */
    protected function postId(): Attribute
    {
        return Attribute::make(
        // get 시에는 DB 컬럼 'post_id' 값을 반환
            get: fn() => $this->attributes['post_id'] ?? null,

            // set 시에는 DB의 'post_id'에 값을 채우도록 재매핑
            set: fn($value) => ['post_id' => $value],
        );
    }
}
