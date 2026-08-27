<?php

declare(strict_types=1);

namespace App\Entities;

final class Feature
{
    private function __construct(
        public readonly int $id,
        public readonly string $feature,
        public readonly int $price,
        public readonly ?string $activity,
        public readonly ?string $priceLevel,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            feature: (string) $row['feature'],
            price: (int) $row['price'],
            activity: isset($row['activity']) ? (string) $row['activity'] : null,
            priceLevel: isset($row['price_level']) ? (string) $row['price_level'] : null,
        );
    }
}
