<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Controller\TagController;

$isExceptionThrown = TagController::deleteTag();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete A Tag</title>
</head>

<body>
    <h1>Delete A Tag</h1>
    <?php if ($isExceptionThrown) { ?>
        <span>Delete all child tags first</span>
    <?php } ?>
    <form action="./delete-tag.php" method="post">
        <input type="hidden" name="tagId" value="<?php echo $_GET['tagId'] ?? '' ?>">
        <button type="submit" name="yes">Yes</button>
        <a href="./tags-list.php">No</a>
    </form>
</body>

</html>