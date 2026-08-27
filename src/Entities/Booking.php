<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeImmutable;

final class Booking
{
    private function __construct(
        public readonly int $id,
        public readonly int $guestId,
        public readonly int $roomId,
        public readonly DateTimeImmutable $checkIn,
        public readonly DateTimeImmutable $checkOut,
        public readonly int $totalPrice,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            guestId: (int) $row['guest_id'],
            roomId: (int) $row['room_id'],
            checkIn: new DateTimeImmutable((string) $row['check_in']),
            checkOut: new DateTimeImmutable((string) $row['check_out']),
            totalPrice: (int) $row['totalprice'],
        );
    }
}
