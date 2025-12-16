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

<!-- calender  -->

<?php
// Days when the room is booked

$roomId = 1;

$statement = $database->prepare("SELECT check_in, check_out FROM bookings WHERE room_id = :room_id");
$statement->execute([':room_id' => $roomId]);

$bookedDays = $statement->fetchAll(PDO::FETCH_ASSOC);

// add booked on all booked days

$booked = [];

foreach ($bookedDays as $bookedDay) {

    $checkInDate = substr($bookedDay['check_in'], 8, 2);
    $checkOutDate = substr($bookedDay['check_out'], 8, 2);
    $bookedDaysRange = range($checkInDate, $checkOutDate);

    $booked = array_merge($booked, $bookedDaysRange);
}

?>


<section class="calendar">
    <?php
    for ($i = 1; $i <= 31; $i++) :
        if (in_array($i, $booked)) {
    ?><div class="day booked"><?= $i; ?></div>
        <?php
        } else if (($i % 7) === 0 || ($i % 7) === 6) {
        ?><div class="day weekend"><?= $i; ?></div>
        <?php
        } else {
        ?><div class="day"><?= $i; ?></div>
    <?php
        }
    endfor; ?>

</section>