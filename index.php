<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

$statement = $database->query('SELECT * FROM rooms');

$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<h1>Welcome to Scubaland!</h1>

<h2>Book your room here</h2>

<h3>Choose date, room and your name</h3>

<form method="post" action="booking.php">

    <label>
        Check-in:
        <input type="date"
            name="check_in"
            min="2026-01-01"
            max="2026-01-31" required>
    </label>

    <label>
        Check-out:
        <input type="date"
            name="check_out"
            min="2026-01-01"
            max="2026-01-31" required>
    </label>
    <label>
        Your name:
        <input type="text" name="name" required>
    </label>

    <button type="submit">Book Room</button>
    <?php foreach ($rooms as $room): ?>
        <label class="rooms">
            <img class="hotelRoom" src=" <?= $room['room_image'] ?>" alt="hotel room">
            <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
            <?= htmlspecialchars($room['type']) ?> –
            <?= (int)$room['price'] ?> $/natt
        </label>
    <?php endforeach; ?>


</form>