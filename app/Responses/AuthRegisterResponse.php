<?php

namespace App\Responses;

use App\Models\User;

class AuthRegisterResponse extends BaseResponse
{
    private int $id;
    private string $email;
    private string $name;

    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->email = $user->email;
        $this->name = $user->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
