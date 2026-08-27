<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;

final class BookingResult
{
    public function __construct(
        public readonly string $guestName,
        public readonly int $roomId,
        public readonly DateTimeImmutable $checkIn,
        public readonly DateTimeImmutable $checkOut,
        public readonly int $nights,
        public readonly int $roomTotal,
        public readonly int $totalPrice,
        public readonly ?string $receiptWarning,
    ) {
    }
}
