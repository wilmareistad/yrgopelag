<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';


$statement = $database->query('SELECT * FROM rooms');

$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<form method="post" action="booking.php">
    <?php foreach ($rooms as $room): ?>
        <label>
            <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
            <?= htmlspecialchars($room['type']) ?> –
            <?= (int)$room['price'] ?> $/natt
        </label>
    <?php endforeach; ?>

    <label>
        Check-in:
        <input type="date"
            name="arrival-date"
            min="2026-01-01"
            max="2026-01-31" required>
    </label>

    <label>
        Check-out:
        <input type="date"
            name="departure_date"
            min="2026-01-01"
            max="2026-01-31" required>
    </label>

    <button type="submit">Boka rum</button>
</form>