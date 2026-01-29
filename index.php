<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';



// get rooms
$statement = $database->query('SELECT * FROM rooms');
$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

// get features
$stmt = $database->query("SELECT * FROM features");
$features = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<main>
    <div class="hero">
        <img src="assets/images/hero.png" alt="">
        <div class="hero-overlay">
            <a href="#h2">
                <h1>Dive into Scubaland</h1>
            </a>
            <p>Rooms, reefs & real adventures</p>
        </div>
    </div>

    <section class="hero-text">
        <h2 id="h2">Welcome to Scubaland</h2>
        <div class="highlight-dot"></div>
        <p>Some of the most unforgettable experiences start beneath the surface. Add exciting dives, vibrant coral reefs, and new friends – and you get the true feeling of Scubaland. Feel good, have fun, explore magical places, and create memories that will last a lifetime on our underwater adventures.</p>
        <h3>Our rooms: where would you like to stay?</h3>
    </section>


    <form class="bookingForm" method="post" action="app/users/booking.php">
        <div class="roomsFeatures">
            <!-- hotelrooms -->

            <?php foreach ($rooms as $room): ?>
                <label class="roomLabel">
                    <div class="hotelContainer">
                        <img class="hotelRoom" src="<?= $room['room_image'] ?>" alt="hotel room">
                        <label class="roomRadio">
                            <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
                            <?= htmlspecialchars($room['type']) ?> – <?= (int)$room['price'] ?> pesos/natt
                        </label>

                        <!-- calender -->
                        <section class="calendar">
                            <?php
                            $statement = $database->prepare("SELECT check_in, check_out FROM bookings WHERE room_id = :room_id");
                            $statement->execute([':room_id' => $room['id']]);
                            $bookedDays = $statement->fetchAll(PDO::FETCH_ASSOC);

                            $booked = [];
                            foreach ($bookedDays as $bookedDay) {
                                $checkInDate = (int)substr($bookedDay['check_in'], 8, 2);
                                $checkOutDate = (int)substr($bookedDay['check_out'], 8, 2) - 1;

                                $bookedDaysRange = range($checkInDate, $checkOutDate);
                                $booked = array_merge($booked, $bookedDaysRange);
                            }

                            $firstOfMonth = date("N", strtotime("2026-01-01"));
                            $daysInMonth = 31;

                            for ($i = 1; $i < $firstOfMonth; $i++) {
                                echo "<div class='day empty'> </div>";
                            }

                            for ($i = 1; $i <= $daysInMonth; $i++) {
                                $dayOfWeek = date("N", strtotime("2026-01-$i"));

                                if (in_array($i, $booked)) {
                                    echo "<div class='day booked'>$i</div>";
                                } elseif ($dayOfWeek >= 6) {
                                    echo "<div class='day weekend'>$i</div>";
                                } else {
                                    echo "<div class='day'>$i</div>";
                                }
                            }

                            ?>
                        </section>
                    </div>
                </label>
            <?php endforeach; ?>


            <!-- features -->
            <div class="featuresContainer">
                <div class="hotelContainer">
                    <h3>Add features</h3>
                    <?php foreach ($features as $feature): ?>
                        <label class="featureCheckbox">
                            <input type="checkbox" name="features[]" value="<?= $feature['feature'] ?>">
                            <?= $feature['feature'] ?>: <?= (int)$feature['price'] ?> pesos
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="hotelContainer" id="booking-form">
            <section class="errors">
                <?php if (!empty($_SESSION['errors'])): ?>
                    <div>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>

                <?php $_SESSION['errors'] = [];
                endif; ?>
            </section>
            <div class="checkinContainer">
                <div class="datescontainer">
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
                </div>
                <label>
                    Your name:
                    <input type="text" name="name" required>
                </label>
                <label>
                    Your transfercode:
                    <input type="text" name="transferCode" placeholder="Your transfer code" required>
                </label>
                <p>Check-in: 15:00 | Check-out: 11:00</p>
                <button class="btn btn-success" type="submit">Book Room</button>
            </div>
    </form>


    <section class="tranfercodeForm"> <?php require __DIR__ . "/book.php" ?> </section>
</main>

<?php require __DIR__ . '/views/footer.php';
