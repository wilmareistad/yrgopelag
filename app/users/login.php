<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Bootstrap;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Support\Csrf;
use App\Support\Redirect;
use App\Support\Session;

$boot = Bootstrap::init(dirname(__DIR__, 2));

$session = new Session();
$csrf = new Csrf();

if (!$csrf->verify($_POST['csrf_token'] ?? null)) {
    $session->flashError('Your session expired, please try again.');
    Redirect::to('/app/login.php');
}

if (!isset($_POST['email'], $_POST['password'])) {
    Redirect::to('/app/login.php');
}

$email = trim($_POST['email']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Redirect::to('/app/login.php');
}

$auth = new AuthService(new UserRepository($boot->pdo()), $session);

if ($auth->attempt($email, $_POST['password'])) {
    Redirect::to('/app/admin.php');
}

$session->flashError('Invalid email or password.');
Redirect::to('/app/login.php');
