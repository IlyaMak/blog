<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';
include '../private-page.php';

use App\Controller\PostController;

$isFailed = PostController::deletePost();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
</head>

<body>
    <h1>Delete Post</h1>
    <?php if ($isFailed) { ?>
        <span>Something went wrong. There was an error</span>
    <?php } ?>
    <form action="./delete-post.php" method="post">
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? '' ?>">
        <button type="submit" name="yes">Yes</button>
        <a href="./posts-list.php">No</a>
    </form>
</body>

</html>