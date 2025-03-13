<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class PasswordEncryptor
{
    /**
     * 해시 생성
     */
    public function encrypt(string $password): string
    {
        // Laravel에서 기본적으로 bcrypt 사용
        // 필요한 경우 Argon2, PBKDF2 등 다른 알고리즘도 세팅 가능
        $saltPassword = $this->addSalt($password);
        return Hash::make($saltPassword);
    }

    /**
     * salt 추가
     */
    private function addSalt(string $password): string
    {
        return $password . 'salt';
    }

    /**
     * 해시 검증
     */
    public function check(string $password, string $hashed): bool
    {
        $saltPassword = $this->addSalt($password);

        return Hash::check($saltPassword, $hashed);
    }
}
