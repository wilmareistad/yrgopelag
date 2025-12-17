<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../views/header.php';

// for guzzle to get bank data
use GuzzleHttp\Client;

if (isset($_POST['room_id'], $_POST['check_in'], $_POST['check_out'], $_POST['name'])) {
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $name = htmlspecialchars(trim($_POST['name']));

    //check out must be greater then check in
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

    <!-- check if the transfercode is okey -->

<?php

    $hotelUser = 'Scubaland';
    $apiKey = $_ENV['API_KEY'];
    $client = new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);

    try {
        $transferCode;
    }
;



    // add the guest if it not exist
    $statement = $database->prepare("SELECT id FROM guests WHERE name = :name");
    $statement->execute([':name' => $name]);
    $guestId = $statement->fetchColumn();

    if (!$guestId) {
        $statement = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
        $statement->execute([':name' => $name]);
        $guestId = (int)$database->lastInsertId();
    }

    // save booking

    $statement = $database->prepare("INSERT INTO bookings (guest_id, room_id, check_in, check_out, totalprice)
    VALUES (:guest_id, :room_id, :check_in, :check_out, :totalprice)");

    $statement->execute([
        ':guest_id' => $guestId,
        ':room_id' => $roomId,
        ':check_in' => $checkIn,
        ':check_out' => $checkOut,
        ':totalprice' => $totalPrice
    ]);
} else {
    echo "Some info is missing";
} ?>