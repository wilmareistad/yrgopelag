<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_POST['room_id'], $_POST['check_in'], $_POST['check_out'], $_POST['name'])) {
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $name = htmlspecialchars(trim($_POST['name']));

    if ($checkOut <= $checkIn) {
        die("Check-out must be after check-in");
    }


    // check if the room is free
    $sql = "SELECT COUNT(*) FROM bookings WHERE room_id = :room_id AND NOT (check_out <= :check_in OR check_in >= :check_out)";

    $statement = $database->prepare($sql);
    $statement->execute([
        ':room_id' => $roomId,
        ':check_in' => $checkIn,
        ':check_out' => $checkOut
    ]);

    if ($statement->fetchColumn() > 0) {
        die('The room is already booked please choose another date');
    }


    // get the price for the hotel
    $statement = $database->prepare('SELECT price FROM rooms where id = :room_id');
    $statement->execute([':room_id' => $roomId]);
    $pricePerNight = (int)$statement->fetchColumn();

    // get the total price
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    $nights = $checkInDate->diff($checkOutDate)->days;
    $totalPrice = $nights * $pricePerNight; ?>

    <h2>nice booking</h2>
    <p>Thanks for your booking <?= $name ?></p>
    <p>Check-in date: <?= $checkIn ?></p>
    <p>Check-out date: <?= $checkOut ?></p>
    <p>Number of nights: <?= $nights ?></p>
    <p>Total price: <?= $totalPrice ?></p>

<?php
} else {
    echo "Some info is missing";
} ?>