<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\PasswordEncryptor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
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

    public function validatePassword(string $password)
    {
        $passwordEncryptor = app(PasswordEncryptor::class);

        return $passwordEncryptor->check($password, $this->password);
    }
}
