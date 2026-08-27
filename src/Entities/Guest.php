<?php

declare(strict_types=1);

namespace App\Entities;

final class Guest
{
    private function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            name: (string) $row['name'],
        );
    }
}
