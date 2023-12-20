<?php

declare(strict_types=1);

if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: /pages/auth/sign-in.php');
    exit;
}
