<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Guest;
use PDO;

final class GuestRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByName(string $name): ?Guest
    {
        $stmt = $this->pdo->prepare('SELECT * FROM guests WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        return $row === false ? null : Guest::fromRow($row);
    }

    public function firstOrCreateByName(string $name): Guest
    {
        $existing = $this->findByName($name);

        if ($existing !== null) {
            return $existing;
        }

        $stmt = $this->pdo->prepare('INSERT INTO guests (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);

        return Guest::fromRow([
            'id' => (int) $this->pdo->lastInsertId(),
            'name' => $name,
        ]);
    }
}
