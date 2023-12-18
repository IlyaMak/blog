<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getVisibleTagsWithParentTagName();
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
    <a href="../tag/create-tag.php">Create tag</a>
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
            </tr>
        <?php } ?>
    </table>
</body>

</html>