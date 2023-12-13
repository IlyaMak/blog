<?php

include '../../src/autoload.php';

use App\Controller\AuthController;

$isFailedRegistration = AuthController::signUp();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <h1>Sign Up</h1>
    <form action="./sign-up.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="repeatPassword" placeholder="Repeat Password">
        <button type="submit">Save</button>
    </form>
    <?php if ($isFailedRegistration) { ?>
        <span>Please, correct fields</span>
    <?php } ?>
</body>

</html>