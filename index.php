<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Bootstrap;
use App\Repositories\BookingRepository;
use App\Repositories\FeatureRepository;
use App\Repositories\RoomRepository;
use App\Services\AvailabilityService;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;

$boot = Bootstrap::init(__DIR__);

$session = new Session();
$csrf = new Csrf();

$roomRepository = new RoomRepository($boot->pdo());
$featureRepository = new FeatureRepository($boot->pdo());
$availability = new AvailabilityService(new BookingRepository($boot->pdo()));

require __DIR__ . '/views/header.php';

View::render(__DIR__ . '/views/home.php', [
    'rooms' => $roomRepository->all(),
    'features' => $featureRepository->all(),
    'availability' => $availability,
    'errors' => $session->pullErrors(),
    'csrf' => $csrf,
]);

require __DIR__ . '/views/footer.php';
