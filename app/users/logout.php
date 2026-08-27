<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Bootstrap;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Support\Redirect;
use App\Support\Session;

$boot = Bootstrap::init(dirname(__DIR__, 2));

$auth = new AuthService(new UserRepository($boot->pdo()), new Session());
$auth->logout();

Redirect::to('/app/login.php');
