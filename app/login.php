<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Bootstrap;
use App\Support\Csrf;
use App\Support\Redirect;
use App\Support\Session;

Bootstrap::init(dirname(__DIR__));

$session = new Session();
$csrf = new Csrf();

if ($session->isLoggedIn()) {
    Redirect::to('/app/admin.php');
}

require __DIR__ . '/../views/header.php';
?>

<article class="adminLogin">
    <h1>Login</h1>
    <form action="/app/users/login.php" method="post">
        <?= $csrf->field() ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input class="form-control" type="email" name="email" id="email" placeholder="francis@darjeeling.com" required>
            <small class="form-text">Please provide the your email address.</small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" type="password" name="password" id="password" required>
            <small class="form-text">Please provide the your password (passphrase).</small>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</article>

<?php require __DIR__ . '/../views/footer.php'; ?>
