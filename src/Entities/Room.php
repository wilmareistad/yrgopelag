<?php

declare(strict_types=1);

namespace App\Entities;

final class Room
{
    private function __construct(
        public readonly int $id,
        public readonly int $price,
        public readonly string $type,
        public readonly string $roomImage,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            price: (int) $row['price'],
            type: (string) $row['type'],
            roomImage: (string) $row['room_image'],
        );
    }
}
