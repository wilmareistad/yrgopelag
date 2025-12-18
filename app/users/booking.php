<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../views/header.php';
// for guzzle to get bank data

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestExepction;

$receipt = null;

if (isset($_POST['room_id'], $_POST['check_in'], $_POST['check_out'], $_POST['name'], $_POST['transferCode'])) {
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $name = htmlspecialchars(trim($_POST['name']));
    $transferCode = $_POST['transferCode'];

    //check out must be greater then check in
    if ($checkOut <= $checkIn) die("Check-out must be after check-in");

    // check if the room is free
    $stmt = $database->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = :room_id AND NOT (check_out <= :check_in OR check_in >= :check_out)");
    $stmt->execute([':room_id' => $roomId, ':check_in' => $checkIn, ':check_out' => $checkOut]);
    if ($stmt->fetchColumn() > 0) die("Room already booked");

    // get the price for the hotel
    $stmt = $database->prepare("SELECT price FROM rooms WHERE id = :room_id");
    $stmt->execute([':room_id' => $roomId]);
    $pricePerNight = (int)$stmt->fetchColumn();
    $nights = (new DateTime($checkIn))->diff(new DateTime($checkOut))->days;
    $totalPrice = $nights * $pricePerNight;

    // add the guest if it not exist
    $stmt = $database->prepare("SELECT id FROM guests WHERE name = :name");
    $stmt->execute([':name' => $name]);
    $guestId = $stmt->fetchColumn();
    if (!$guestId) {
        $stmt = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        $guestId = (int)$database->lastInsertId();
    }

    // Guzzle
    $hotelUser = 'Wilma';
    $apiKey = $_ENV['API_KEY'];
    $client = new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);

    try {
        // Validate transferCode
        $res = $client->post('transferCode', [
            'json' => ['transferCode' => $transferCode, 'totalCost' => $totalPrice]
        ]);
        $validate = json_decode($res->getBody(), true);
        if (isset($validate['error'])) throw new Exception($validate['error']);

        // Deposit
        $res = $client->post('deposit', [
            'json' => ['user' => $hotelUser, 'transferCode' => $transferCode]
        ]);
        $deposit = json_decode($res->getBody(), true);
        if (!isset($deposit['status']) || $deposit['status'] !== "success") throw new Exception($deposit['error'] ?? "Deposit failed");

        // Save booking
        $stmt = $database->prepare("INSERT INTO bookings (guest_id, room_id, check_in, check_out, totalprice) VALUES (:guest_id, :room_id, :check_in, :check_out, :totalprice)");
        $stmt->execute([
            ':guest_id' => $guestId,
            ':room_id' => $roomId,
            ':check_in' => $checkIn,
            ':check_out' => $checkOut,
            ':totalprice' => $totalPrice
        ]);

        // Send receipt to centralbank
        $client->post('receipt', [
            'json' => [
                'user' => $hotelUser,
                'api_key' => $apiKey,
                'guest_name' => $name,
                'arrival_date' => $checkIn,
                'departure_date' => $checkOut,
                'features_used' => [],
                'star_rating' => 5
            ]
        ]);

        // Receipt for frontend
        $receipt = [
            'guest' => $name,
            'roomId' => $roomId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'nights' => $nights,
            'totalPrice' => $totalPrice
        ];
    } catch (Exception $e) {
        echo "<p style='color:red'>Fel: " . $e->getMessage() . "</p>";
    }
}
?>

<?php if ($receipt): ?>
    <h2>Receipt</h2>
    <p>Guest: <?= $receipt['guest'] ?></p>
    <p>Room ID: <?= $receipt['roomId'] ?></p>
    <p>Check-in: <?= $receipt['check_in'] ?></p>
    <p>Check-out: <?= $receipt['check_out'] ?></p>
    <p>Nights: <?= $receipt['nights'] ?></p>
    <p>Total Price: <?= $receipt['totalPrice'] ?> $</p>
    <p>Status: Booking confirmed and paid!</p>
<?php endif; ?>