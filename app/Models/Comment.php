<?php

namespace App\Models;

use App\Services\PasswordEncryptor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author',
        'password',
        'content',
        'postId',
        'userId',
    ];

    private int $postId;
    private string $author;
    private string $password;
    private string $content;
    private int $userId;

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

    // password에 할당이 일어날 때 자동으로 해싱
    public function setPasswordAttribute($value)
    {
        // password가 비어 있지 않다면 Hash::make
        // (만약 null 허용이면 조건 분기)
        if (!empty($value)) {
            $passwordEncryptor = app(PasswordEncryptor::class);
            $this->attributes['password'] = $passwordEncryptor->encrypt($value);
        }
    }
}
