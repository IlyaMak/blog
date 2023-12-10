<?php

include '../../src/index.php';

use Controller\TagController;
use Repository\TagRepository;
use Service\DatabaseConnector;

$isFailed = TagController::createTag();
$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getTags();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create A Tag</title>
</head>

<body>
    <h1>Create A Tag</h1>
    <form action="./create-tag.php" method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="radio" name="isVisible">Is visible?
        <select name="parentTag">
            <option value="">Select a parent tag (optional)</option>
            <?php if (is_array($tags)) { ?>
                <?php for ($i = 0; $i <= count($tags) - 1; $i++) { ?>
                    <option value=<?php echo $tags[$i]['id'] ?>>
                        <?php echo $tags[$i]['name'] ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>
        <button type="submit">Save</button>
    </form>
    <?php if ($isFailed) { ?>
        <span>Correct fields or this tag name is already exists</span>
    <?php } ?>
</body>

</html>