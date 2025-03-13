<?php

namespace App\Responses;

use App\Models\User;

class UserResponse extends BaseResponse
{
    private int $id;
    private string $email;
    private string $createdAt;
    private string $updatedAt;
    private string $name;

    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->email = $user->email;
        $this->createdAt = $user->created_at;
        $this->updatedAt = $user->updated_at;
        $this->name = $user->name;
    }
}
