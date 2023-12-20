<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';
include '../private-page.php';

use App\Controller\PostController;
use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getTags();
$isFailed = PostController::createPost($db);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create A Post</title>
</head>

<body>
    <h1>Create A Post</h1>
    <form action="./create-post.php" method="post" enctype="multipart/form-data">
        <div>
            <input type="text" name="headline" placeholder="Headline" required />
        </div>
        <div>
            <textarea name="body" placeholder="Body" required></textarea>
        </div>
        <div>
            <label for="tags">Select tags</label>
            <select name="tags[]" id="tags" multiple>
                <?php for ($i = 0; $i <= count($tags) - 1; $i++) { ?>
                    <option value="<?php echo $tags[$i]['id'] ?>">
                        <?php echo $tags[$i]['name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="publishDate">Publish Datetime</label>
            <input type="datetime-local" name="publishDate" id="publishDate" />
        </div>
        <div>
            <label for="image">Preview image</label>
            <input type="file" name="image" id="image" size="2000000" required />
        </div>
        <div>
            <input type="checkbox" id="isVisible" name="isVisible" value="1" checked />
            <label for="isVisible">Is visible?</label>
        </div>
        <button type="submit">Save</button>
    </form>
    <?php if ($isFailed) { ?>
        <span>Please, correct fields</span>
    <?php } ?>
</body>

</html>