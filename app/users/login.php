<?php

require __DIR__ . '/../autoload.php';

if (!isset($_POST['email'], $_POST['password'])) {
    header('Location: /yrgopelag/app/login.php');;
    exit;
}

$email = trim($_POST['email']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /yrgopelag/app/login.php');;
    exit;
}

$stmt = $database->prepare(
    'SELECT * FROM users WHERE email = :email'
);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /yrgopelag/app/login.php');
    exit;
}

if (password_verify($_POST['password'], $user['password'])) {
    $_SESSION['user'] = $user;
    header('Location: /yrgopelag/app/admin.php');
    exit;
}

header('Location: /yrgopelag/app/login.php');
exit;
