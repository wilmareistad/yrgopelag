<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Feature;
use PDO;

final class FeatureRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return Feature[] */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM features');

        return array_map(Feature::fromRow(...), $stmt->fetchAll());
    }

    public function find(int $id): ?Feature
    {
        $stmt = $this->pdo->prepare('SELECT * FROM features WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : Feature::fromRow($row);
    }

    public function findByName(string $name): ?Feature
    {
        $stmt = $this->pdo->prepare('SELECT * FROM features WHERE feature = :feature');
        $stmt->execute(['feature' => $name]);
        $row = $stmt->fetch();

        return $row === false ? null : Feature::fromRow($row);
    }

    public function updatePrice(int $id, int $price): bool
    {
        $stmt = $this->pdo->prepare('UPDATE features SET price = :price WHERE id = :id');

        return $stmt->execute(['price' => $price, 'id' => $id]);
    }
}
