<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Bootstrap;
use App\Exceptions\BookingException;
use App\Exceptions\PaymentException;
use App\Http\CentralBankClient;
use App\Repositories\BookingRepository;
use App\Repositories\FeatureRepository;
use App\Repositories\GuestRepository;
use App\Repositories\RoomRepository;
use App\Services\AvailabilityService;
use App\Services\BookingRequest;
use App\Services\BookingService;
use App\Services\PricingService;
use App\Support\Csrf;
use App\Support\Redirect;
use App\Support\Session;
use App\Support\View;

$boot = Bootstrap::init(dirname(__DIR__, 2));

$session = new Session();
$csrf = new Csrf();

if (!$csrf->verify($_POST['csrf_token'] ?? null)) {
    $session->flashError('Your session expired, please try again.');
    Redirect::to('/index.php#booking-form');
}

$pdo = $boot->pdo();

$bookingService = new BookingService(
    new RoomRepository($pdo),
    new FeatureRepository($pdo),
    new GuestRepository($pdo),
    new BookingRepository($pdo),
    new AvailabilityService(new BookingRepository($pdo)),
    new PricingService(),
    new CentralBankClient($boot->config()),
);

try {
    $request = BookingRequest::fromArray($_POST);
    $result = $bookingService->book($request);
} catch (BookingException|PaymentException $e) {
    $session->flashError($e->getMessage());
    Redirect::to('/index.php#booking-form');
}

require __DIR__ . '/../../views/header.php';

View::render(__DIR__ . '/../../views/receipt.php', ['result' => $result]);

require __DIR__ . '/../../views/footer.php';
