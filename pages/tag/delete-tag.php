<?php

use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

include '../../src/autoload.php';

$isCannotDeleteExceptionTrow = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DatabaseConnector::getDatabaseConnection();
    $tagRepository = new TagRepository($db);
    try {
        $tagRepository->deleteTag($_POST['tagId']);
    } catch (PDOException $e) {
        $isCannotDeleteExceptionTrow = true;
    }
    if (!$isCannotDeleteExceptionTrow) {
        header('Location: tags-list.php');
    }
}

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
    <?php if ($isCannotDeleteExceptionTrow) { ?>
        <span>Delete all child tags first</span>
    <?php } ?>
    <form action="./delete-tag.php" method="post">
        <input type="hidden" name="tagId" value="<?php echo $_GET['tagId'] ?? '' ?>">
        <button type="submit" name="yes">Yes</button>
        <a href="./tags-list.php">No</a>
    </form>
</body>

</html>