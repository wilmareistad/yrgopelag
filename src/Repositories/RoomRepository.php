<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Room;
use PDO;

final class RoomRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return Room[] */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM rooms');

        return array_map(Room::fromRow(...), $stmt->fetchAll());
    }

    public function find(int $id): ?Room
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : Room::fromRow($row);
    }

    public function updatePrice(int $id, string $type, int $price): bool
    {
        $stmt = $this->pdo->prepare('UPDATE rooms SET price = :price WHERE id = :id AND type = :type');

        return $stmt->execute(['price' => $price, 'id' => $id, 'type' => $type]);
    }
}
