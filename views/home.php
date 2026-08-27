<?php

/** @var App\Entities\Room[] $rooms */
/** @var App\Entities\Feature[] $features */
/** @var App\Services\AvailabilityService $availability */
/** @var string[] $errors */
/** @var App\Support\Csrf $csrf */

use App\Support\View;

?>


<main>
    <div class="hero">
        <img src="/assets/images/hero.png" alt="">
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


    <form class="bookingForm" method="post" action="/app/users/booking.php">
        <?= $csrf->field() ?>
        <div class="roomsFeatures">
            <!-- hotelrooms -->

            <?php foreach ($rooms as $room): ?>
                <label class="roomLabel">
                    <div class="hotelContainer">
                        <img class="hotelRoom" src="<?= View::e($room->roomImage) ?>" alt="hotel room">
                        <label class="roomRadio">
                            <input type="radio" name="room_id" value="<?= $room->id ?>" required>
                            <?= View::e($room->type) ?> – <?= $room->price ?> pesos/natt
                        </label>

                        <!-- calender -->
                        <section class="calendar">
                            <?php $calendar = $availability->buildCalendar($room->id); ?>
                            <?php for ($i = 0; $i < $calendar['leadingEmptyDays']; $i++): ?>
                                <div class="day empty"> </div>
                            <?php endfor; ?>
                            <?php foreach ($calendar['days'] as $dayInfo): ?>
                                <div class="day<?= $dayInfo['status'] !== 'available' ? ' ' . $dayInfo['status'] : '' ?>"><?= $dayInfo['day'] ?></div>
                            <?php endforeach; ?>
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
                            <input type="checkbox" name="features[]" value="<?= View::e($feature->feature) ?>">
                            <?= View::e($feature->feature) ?>: <?= $feature->price ?> pesos
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="hotelContainer" id="booking-form">
            <section class="errors">
                <?php if (!empty($errors)): ?>
                    <div>
                        <?php foreach ($errors as $error): ?>
                            <p><?= View::e($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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


    <section class="tranfercodeForm"> <?php require __DIR__ . '/../app/book.php'; ?> </section>
</main>
