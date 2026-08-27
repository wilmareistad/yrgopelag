<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Feature;
use App\Entities\Room;
use DateTimeInterface;

final class PricingService
{
    public function nights(DateTimeInterface $checkIn, DateTimeInterface $checkOut): int
    {
        return $checkIn->diff($checkOut)->days;
    }

    public function roomTotal(Room $room, int $nights): int
    {
        return $room->price * $nights;
    }

    /**
     * @param Feature[] $features
     * @param string[] $selectedNames
     */
    public function featuresTotal(array $features, array $selectedNames): int
    {
        $byName = [];
        foreach ($features as $feature) {
            $byName[$feature->feature] = $feature;
        }

        $total = 0;
        foreach ($selectedNames as $name) {
            if (isset($byName[$name])) {
                $total += $byName[$name]->price;
            }
        }

        return $total;
    }
}
