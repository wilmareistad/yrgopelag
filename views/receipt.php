<?php

/** @var App\Services\BookingResult $result */

use App\Support\View;

?>
<section class="receiptContainer">
    <div class="receipt">
        <h2>Receipt</h2>
        <p>Guest: <?= View::e($result->guestName) ?></p>
        <p>Room ID: <?= $result->roomId ?></p>
        <p>Check-in: <?= $result->checkIn->format('Y-m-d') ?></p>
        <p>Check-out: <?= $result->checkOut->format('Y-m-d') ?></p>
        <p>Nights: <?= $result->nights ?></p>
        <p>Hotel Price: <?= $result->roomTotal ?> pesos</p>
        <p>Total Price: <?= $result->totalPrice ?> pesos</p>
        <p>Status: Booking confirmed and paid!</p>
        <?php if ($result->receiptWarning !== null): ?>
            <p><?= View::e($result->receiptWarning) ?></p>
        <?php endif; ?>
    </div>
</section>
