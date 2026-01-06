<?php

require __DIR__ . '/../autoload.php';

// In this file we logout users.
var_dump($_SESSION);

$_SESSION['user'] = null;
header('Location: /login.php');
