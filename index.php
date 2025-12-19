<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

// get rooms
$statement = $database->query('SELECT * FROM rooms');
$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

// get features
$stmt = $database->query("SELECT * FROM features");
$features = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<main>
    <h2 id="h1">Welcome to Scubaland!</h2>

    <h3>Choose date, room and your name</h3>

    <form method="post" action="/app/users/booking.php">

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
        <label>
            Your transfercode:
            <input type="text" name="transferCode" placeholder="Your transfer code" required>
        </label>

        <button type="submit">Book Room</button>

        <div class="rooms">
            <?php foreach ($rooms as $room): ?>
                <label class="roomLabel">
                    <div class="hotelContainer">
                        <img class="hotelRoom" src=" <?= $room['room_image'] ?>" alt="hotel room">
                        <div>
                            <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
                            <?= htmlspecialchars($room['type']) ?> –
                            <?= (int)$room['price'] ?> pesos/natt
                        </div>

                        <?php
                        $statement = $database->prepare("SELECT check_in, check_out FROM bookings WHERE room_id = :room_id");
                        $statement->execute([':room_id' => $room['id']]);

                        $bookedDays = $statement->fetchAll(PDO::FETCH_ASSOC);

                        $booked = [];


                        // add booked on all booked days


                        foreach ($bookedDays as $bookedDay) {

                            $checkInDate = substr($bookedDay['check_in'], 8, 2);
                            $checkOutDate = substr($bookedDay['check_out'], 8, 2);
                            $bookedDaysRange = range($checkInDate, $checkOutDate);

                            $booked = array_merge($booked, $bookedDaysRange);
                        }
                        ?>


                        <!-- calender  -->

                        <!-- Days when the room is booked -->


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
                                } ?>

                            <?php endfor; ?>
                        </section>

                    </div>
                </label>

            <?php endforeach; ?>

        </div>

        <div>
            <?php foreach ($features as $feature): ?>
                <label>
                    <input type="checkbox" name="features[]" value="<?= $feature['feature'] ?>">
                    <?= $feature['feature'] ?> (<?= (int)$feature['price'] ?> pesos)
                </label>
            <?php endforeach; ?>
        </div>
    </form>



    <section class="tranfercodeForm"> <?php require __DIR__ . "/book.php" ?> </section>
</main>

<?php require __DIR__ . '/views/footer.php';
