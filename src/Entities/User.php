<?php

declare(strict_types=1);

namespace App\Entities;

final class User
{
    private function __construct(
        public readonly string $name,
        public readonly string $email,
        private readonly string $passwordHash,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            name: (string) $row['name'],
            email: (string) $row['email'],
            passwordHash: (string) $row['password'],
        );
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }
}
