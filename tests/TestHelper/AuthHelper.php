<?php

namespace Tests\TestHelper;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait AuthHelper
{
    public function actingAsAuthenticatedUser($user = null): User
    {
        $user = $user ?? User::factory()->create();
        Sanctum::actingAs($user); // Sanctum으로 로그인 처리
        return $user;
    }
}
