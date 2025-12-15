<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';


$statement = $database->query('SELECT * FROM rooms');

$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

?> <ul>
    <?php
    foreach ($rooms as $room) {
    ?>
        <li><?= $room['type']; ?> </li>
    <?php
    }
    ?>
</ul>