<?php require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

session_start();

echo $_SESSION['receipt'];
