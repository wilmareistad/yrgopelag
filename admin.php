<?php

require __DIR__ . '/views/header.php';
require __DIR__ . '/app/autoload.php';

// update the price on the rooms

if (isset($_POST['type'], $_POST['id'], $_POST['price'])) {
    $id = (int) $_POST['id'];
    $price = (int) $_POST['price'];

    $stmt = $database->prepare("UPDATE rooms SET price = ? WHERE id = ? AND type = ?");
    $stmt->execute([$price, $id, $type]);

    echo "Rummet är uppdaterat!";
}

$rooms = $database->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="adminRoom">
    <table>
        <h2>Admin: update room</h2>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Price</th>
            <th>Update</th>
        </tr>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= $room['id'] ?></td>
                <td><?= $room['type'] ?></td>
                <td><?= $room['price'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $room['id'] ?>">
                        <input type="hidden" name="type" value="<?= $room['type'] ?>">
                        <input type="number" name="price" value="<?= $room['price'] ?>" required>
                        <button type="submit">Spara</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require __DIR__ . '/views/footer.php';
