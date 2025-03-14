<?php

namespace Tests\TestHelper;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait AuthHelper
{
    /**
     * 인증된 사용자로 로그인 처리
     *
     * @return User
     */
    public function actingAsAuthenticatedUser($user = null): User
    {
        $user = $user ?? User::factory()->create();
        Sanctum::actingAs($user); // Sanctum으로 로그인 처리
        return $user;
    }
}
