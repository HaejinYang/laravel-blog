<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * 포스트(게시글) 모델 클래스
 *
 * @property int $userId 포트스 소유자 ID
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
        'userId'
    ];

    private int $userId;
    private string $title;
    private string $content;
    private string $author;

    /**
     * userId 라는 모델 속성을
     * 내부적으로 user_id 컬럼에 읽고/쓰도록 연결
     */
    protected function userId(): Attribute
    {
        return Attribute::make(
        // get 시에는 DB 컬럼 'user_id' 값을 반환
            get: fn() => $this->attributes['user_id'] ?? null,

            // set 시에는 DB의 'user_id'에 값을 채우도록 재매핑
            set: fn($value) => ['user_id' => $value],
        );
    }
}
