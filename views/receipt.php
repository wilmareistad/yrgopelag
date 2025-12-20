<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!isset($_SESSION['receipt'])) {
    header('Location: /');
    exit;
}

$receipt = $_SESSION['receipt'];
unset($_SESSION['receipt']);
?>

<div class="receipt">
    <h2>Receipt</h2>
    <p>Guest: <?= htmlspecialchars($receipt['guest']) ?></p>
    <p>Room ID: <?= $receipt['roomId'] ?></p>
    <p>Check-in: <?= $receipt['check_in'] ?></p>
    <p>Check-out: <?= $receipt['check_out'] ?></p>
    <p>Nights: <?= $receipt['nights'] ?></p>
    <p>Hotel Price: <?= $receipt['totalPrice'] ?> pesos</p>
    <p>Total Price: <?= $receipt['totalPriceForEverything'] ?> pesos</p>
    <p>Status: Booking confirmed and paid!</p>
</div>