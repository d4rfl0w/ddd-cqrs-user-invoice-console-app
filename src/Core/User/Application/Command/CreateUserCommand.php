<?php

namespace App\Core\User\Application\Command;

class CreateUserCommand
{
    public function __construct(
        public readonly string $email,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }
}
