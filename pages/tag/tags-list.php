<?php

declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);

$currentPage = 0;
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

const LIMIT = 10;
$offset = ($currentPage - 1) * LIMIT;
$tags = $tagRepository->getLimitedVisibleTagsWithParentTagName($offset, LIMIT);

$allTagsAmount = count($tagRepository->getVisibleTagsWithParentTagName());
$pagesAmount = ceil($allTagsAmount / LIMIT);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tags List</title>
</head>

<body>
    <h1>Tags List</h1>
    <a href="../tag/create-update-tag.php">Create tag</a>
    <table>
        <tr>
            <th>Name</th>
            <th>Parent tag</th>
        </tr>
        <?php for ($i = 0; $i <= count($tags) - 1; $i++) { ?>
            <tr>
                <td><?php echo $tags[$i]['name'] ?></td>
                <td>
                    <?php echo $tags[$i]['parent_tag_name'] ?>
                </td>
                <td>
                    <form action="./delete-tag.php" method="get">
                        <input type="hidden" name="tagId" value="<?php echo $tags[$i]['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
                <td>
                    <form action="./create-update-tag.php" method="get">
                        <input type="hidden" name="id" value="<?php echo $tags[$i]['id'] ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php for ($page = 1; $page <= $pagesAmount; $page++) { ?>
        <a href="tags-list.php?page=<?= $page ?>"><?= $page ?></a>
    <?php } ?>
</body>

</html>