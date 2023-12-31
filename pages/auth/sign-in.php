<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Controller\AuthController;

$isSuccessAuthentication = AuthController::signIn();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isSuccessAuthentication) {
    header('Location: ../post/posts-list.php');
};
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>

<body>
    <h1>Sign In</h1>
    <form action="./sign-in.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Save</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isSuccessAuthentication) { ?>
        <span>No user found</span>
    <?php } ?>
    <span>New to this website? <a href="sign-up.php">Join now</a></span>
</body>

</html>