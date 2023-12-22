<?php

declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';
include '../private-page.php';

use App\Controller\TagController;
use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getTags() === false ? [] : (array) $tagRepository->getTags();
$tagId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$tag = null;
if ($tagId !== null) {
    $tag = $tagRepository->getTag($tagId);
}
$isFailed = TagController::createOrUpdateTag($db);
$pageName = $tagId == null ? 'Create A Tag' : 'Update A tag';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageName ?></title>
</head>

<body>
    <h1><?= $pageName ?></h1>
    <form action='./create-update-tag.php<?= isset($tagId) ? "?id=$tagId" : '' ?>' method="post">
        <input type="hidden" name="id" value="<?= $tagId ?>">
        <input type="text" name="name" placeholder="Name" value="<?= $tag['name'] ?? '' ?>">
        Is Visible?
        <label><input type="radio" name="isVisible" value="1" <?= ((isset($tagId) && $tag['is_visible'] == true || $tagId === null)) ? 'checked' : '' ?>>
            Yes
        </label>
        <label><input type="radio" name="isVisible" value="0" <?= isset($tagId) && $tag['is_visible'] == false ? 'checked' : '' ?>>
            No
        </label>
        <select name="parentTagId">
            <option value="">Select a parent tag (optional)</option>
            <?php if (is_array($tags)) { ?>
                <?php for ($i = 0; $i <= count($tags) - 1; $i++) {
                    if ($tagId === $tags[$i]['id']) {
                        continue;
                    } ?>
                    <option value="<?php echo $tags[$i]['id'] ?>" <?= isset($tag['parent_tag_id']) && $tag['parent_tag_id'] === $tags[$i]['id'] ? "selected" : "" ?>>
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