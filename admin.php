<?php

require __DIR__ . '/app/autoload.php';

// validate the user

if (!isset($_SESSION['user'])) {
    header('Location: /yrgopelag/login.php');
    exit;
}

// update the price on the rooms

if (isset($_POST['room_type'], $_POST['room_id'], $_POST['room_price'])) {
    $id = (int) $_POST['room_id'];
    $price = (int) $_POST['room_price'];
    $type = $_POST['room_type'];

    $stmt = $database->prepare("UPDATE rooms SET price = ? WHERE id = ? AND type = ?");
    $stmt->execute([$price, $id, $type]);

    echo "Room is updated!";
}

// features

if (isset($_POST['feature_id'], $_POST['feature_price'])) {
    $id = (int) $_POST['feature_id'];
    $price = (int) $_POST['feature_price'];

    $stmt = $database->prepare("UPDATE features SET price = ? WHERE id = ?");
    $stmt->execute([$price, $id]);

    echo "Feature is updated!";
}

$rooms = $database->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);
$features = $database->query("SELECT * FROM features")->fetchAll(PDO::FETCH_ASSOC);

// rooms
require __DIR__ . '/views/header.php';
?>
<div class="adminRoom">
    <table>
        <h2>Admin: update room</h2>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Price</th>
        </tr>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= $room['id'] ?></td>
                <td><?= $room['type'] ?></td>
                <td><?= $room['price'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                        <input type="hidden" name="room_type" value="<?= $room['type'] ?>">
                        <input type="number" name="room_price" value="<?= $room['price'] ?>" required>
                        <button type="submit">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- features -->
<div class="adminFeatures">
    <h2>Admin: Update features</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Price</th>
        </tr>
        <?php foreach ($features as $feature): ?>
            <tr>
                <td><?= $feature['id'] ?></td>
                <td><?= $feature['price_level'] ?></td>
                <td><?= $feature['price'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="feature_id" value="<?= $feature['id'] ?>">
                        <input type="number" name="feature_price" value="<?= $feature['price'] ?>" required>
                        <button type="submit">Save</button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require __DIR__ . '/views/footer.php';
