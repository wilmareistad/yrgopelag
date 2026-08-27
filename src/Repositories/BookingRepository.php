<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Booking;
use DateTimeInterface;
use PDO;

final class BookingRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return Booking[] */
    public function forRoom(int $roomId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM bookings WHERE room_id = :room_id');
        $stmt->execute(['room_id' => $roomId]);

        return array_map(Booking::fromRow(...), $stmt->fetchAll());
    }

    public function overlaps(int $roomId, DateTimeInterface $checkIn, DateTimeInterface $checkOut): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM bookings
             WHERE room_id = :room_id
             AND NOT (check_out <= :check_in OR check_in >= :check_out)'
        );
        $stmt->execute([
            'room_id' => $roomId,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(int $guestId, int $roomId, DateTimeInterface $checkIn, DateTimeInterface $checkOut, int $totalPrice): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO bookings (guest_id, room_id, check_in, check_out, totalprice)
             VALUES (:guest_id, :room_id, :check_in, :check_out, :totalprice)'
        );
        $stmt->execute([
            'guest_id' => $guestId,
            'room_id' => $roomId,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'totalprice' => $totalPrice,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function attachFeature(int $bookingId, int $featureId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO feature_booking (booking_id, feature_id) VALUES (:booking_id, :feature_id)'
        );
        $stmt->execute(['booking_id' => $bookingId, 'feature_id' => $featureId]);
    }
}
