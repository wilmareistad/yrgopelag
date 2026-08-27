<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Bootstrap;
use App\Repositories\FeatureRepository;
use App\Repositories\RoomRepository;
use App\Support\Csrf;
use App\Support\Redirect;
use App\Support\Session;
use App\Support\View;

$boot = Bootstrap::init(dirname(__DIR__));

$session = new Session();
$csrf = new Csrf();

if (!$session->isLoggedIn()) {
    Redirect::to('/app/login.php');
}

$roomRepository = new RoomRepository($boot->pdo());
$featureRepository = new FeatureRepository($boot->pdo());

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$csrf->verify($_POST['csrf_token'] ?? null)) {
        $message = 'Security check failed, please try again.';
    } elseif (isset($_POST['room_type'], $_POST['room_id'], $_POST['room_price'])) {
        $roomRepository->updatePrice((int) $_POST['room_id'], $_POST['room_type'], (int) $_POST['room_price']);
        $message = 'Room is updated!';
    } elseif (isset($_POST['feature_id'], $_POST['feature_price'])) {
        $featureRepository->updatePrice((int) $_POST['feature_id'], (int) $_POST['feature_price']);
        $message = 'Feature is updated!';
    }
}

require __DIR__ . '/../views/header.php';

if ($message !== null) {
    echo '<p>' . View::e($message) . '</p>';
}

View::render(__DIR__ . '/../views/admin.php', [
    'rooms' => $roomRepository->all(),
    'features' => $featureRepository->all(),
    'csrf' => $csrf,
]);

require __DIR__ . '/../views/footer.php';
