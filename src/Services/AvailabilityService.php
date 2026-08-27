<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BookingRepository;
use DateTimeImmutable;
use DateTimeInterface;

final class AvailabilityService
{
    public function __construct(private readonly BookingRepository $bookings)
    {
    }

    /**
     * @return array{leadingEmptyDays: int, days: array<int, array{day: int, status: string}>}
     */
    public function buildCalendar(int $roomId, ?DateTimeImmutable $month = null): array
    {
        $month = ($month ?? new DateTimeImmutable())->modify('first day of this month');
        $daysInMonth = (int) $month->format('t');
        $leadingEmptyDays = ((int) $month->format('N')) - 1;
        $bookedDays = $this->bookedDaysInMonth($roomId, $month);

        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $month->modify(sprintf('+%d days', $day - 1));
            $isWeekend = ((int) $date->format('N')) >= 6;

            $days[] = [
                'day' => $day,
                'status' => match (true) {
                    in_array($day, $bookedDays, true) => 'booked',
                    $isWeekend => 'weekend',
                    default => 'available',
                },
            ];
        }

        return [
            'leadingEmptyDays' => $leadingEmptyDays,
            'days' => $days,
        ];
    }

    public function isAvailable(int $roomId, DateTimeInterface $checkIn, DateTimeInterface $checkOut): bool
    {
        return !$this->bookings->overlaps($roomId, $checkIn, $checkOut);
    }

    /** @return int[] */
    private function bookedDaysInMonth(int $roomId, DateTimeImmutable $month): array
    {
        $monthStart = $month;
        $monthEnd = $month->modify('first day of next month');

        $booked = [];

        foreach ($this->bookings->forRoom($roomId) as $booking) {
            $start = max($booking->checkIn, $monthStart);
            $end = min($booking->checkOut, $monthEnd);

            if ($start >= $end) {
                continue;
            }

            for ($cursor = $start; $cursor < $end; $cursor = $cursor->modify('+1 day')) {
                $booked[] = (int) $cursor->format('j');
            }
        }

        return $booked;
    }
}
