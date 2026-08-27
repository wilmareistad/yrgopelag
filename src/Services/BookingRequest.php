<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BookingException;
use DateTimeImmutable;

final class BookingRequest
{
    /** @param string[] $features */
    private function __construct(
        public readonly int $roomId,
        public readonly DateTimeImmutable $checkIn,
        public readonly DateTimeImmutable $checkOut,
        public readonly string $name,
        public readonly string $transferCode,
        public readonly array $features,
    ) {
    }

    public static function fromArray(array $post): self
    {
        foreach (['room_id', 'check_in', 'check_out', 'name', 'transferCode'] as $field) {
            if (!isset($post[$field]) || $post[$field] === '') {
                throw new BookingException('Please fill in all required fields.');
            }
        }

        $checkIn = self::parseDate($post['check_in']);
        $checkOut = self::parseDate($post['check_out']);

        if ($checkIn === null || $checkOut === null) {
            throw new BookingException('Please provide valid check-in and check-out dates.');
        }

        if ($checkOut <= $checkIn) {
            throw new BookingException('Check-out must be after check-in.');
        }

        $name = trim((string) $post['name']);

        if ($name === '') {
            throw new BookingException('Please provide your name.');
        }

        $features = array_values(array_filter(
            (array) ($post['features'] ?? []),
            static fn ($feature) => is_string($feature) && $feature !== ''
        ));

        return new self(
            roomId: (int) $post['room_id'],
            checkIn: $checkIn,
            checkOut: $checkOut,
            name: $name,
            transferCode: (string) $post['transferCode'],
            features: $features,
        );
    }

    private static function parseDate(mixed $value): ?DateTimeImmutable
    {
        if (!is_string($value)) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if ($date === false || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $date;
    }
}
