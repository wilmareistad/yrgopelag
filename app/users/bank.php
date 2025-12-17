<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';


if (isset($_POST['transferCode'], $_POST['totalPrice'], $_POST['guest_id'], $_POST['room_id'], $_POST['check_in'], $_POST['check_out'])) {
    $transferCode = $_POST['transferCode'];
    $totalPrice = (int)$_POST['totalPrice'];
    $guestId = (int)$_POST['guest_id'];
    $roomId = (int)$_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
}
