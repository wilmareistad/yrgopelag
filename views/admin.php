<?php

/** @var App\Entities\Room[] $rooms */
/** @var App\Entities\Feature[] $features */
/** @var App\Support\Csrf $csrf */

use App\Support\View;

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
                <td><?= $room->id ?></td>
                <td><?= View::e($room->type) ?></td>
                <td><?= $room->price ?></td>
                <td>
                    <form method="POST">
                        <?= $csrf->field() ?>
                        <input type="hidden" name="room_id" value="<?= $room->id ?>">
                        <input type="hidden" name="room_type" value="<?= View::e($room->type) ?>">
                        <input type="number" name="room_price" value="<?= $room->price ?>" required>
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
                <td><?= $feature->id ?></td>
                <td><?= View::e($feature->priceLevel) ?></td>
                <td><?= $feature->price ?></td>
                <td>
                    <form method="POST">
                        <?= $csrf->field() ?>
                        <input type="hidden" name="feature_id" value="<?= $feature->id ?>">
                        <input type="number" name="feature_price" value="<?= $feature->price ?>" required>
                        <button type="submit">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
