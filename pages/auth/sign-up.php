<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

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
    <span>Already have an account? <a href="sign-in.php">Sign in</a></span>
</body>

</html>