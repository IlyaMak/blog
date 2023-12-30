<?php

declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Repository\PostRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$postRepository = new PostRepository($db);
$sessionId = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;
$posts = $postRepository->getPosts($sessionId);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts List</title>
</head>

<body>
    <h1>Posts List</h1>
    <a href="./create-update-post.php">Create A Post</a>
    <a href="../tag/tags-list.php">Tags List</a>
    <table>
        <tr>
            <th>Image</th>
            <th>Headline</th>
            <th>Body</th>
            <th>Tags</th>
        </tr>
        <?php if (count($posts) > 0) {
            for ($i = 0; $i < count($posts); $i++) {
                $isPostOwner = $sessionId === $posts[$i]['user_id']; ?>
                <tr>
                    <td>
                        <img src="<?php echo $posts[$i]['image_path'] ?>" alt="post image" width="100px" />
                    </td>
                    <td>
                        <?php echo strlen($posts[$i]['headline']) > 50
                            ? substr($posts[$i]['headline'], 0, 50) . '...'
                            : $posts[$i]['headline'] ?>
                    </td>
                    <td>
                        <?php echo strlen($posts[$i]['body']) > 100
                            ? substr($posts[$i]['body'], 0, 100) . '...'
                            : $posts[$i]['body'] ?>
                    </td>
                    <td>
                        <?php echo $posts[$i]['tags'] ?>
                    </td>
                    <td>
                        <a href="./show-post.php?id=<?php echo $posts[$i]['id'] ?>">View</a>
                    </td>
                    <?php if ($isPostOwner) { ?>
                        <td>
                            <a href="./create-update-post.php?id=<?php echo $posts[$i]['id'] ?>">Update</a>
                        </td>
                        <td>
                            <a href="./delete-post.php?id=<?php echo $posts[$i]['id'] ?>">Delete</a>
                        </td>
                        <?php if (!$posts[$i]['is_visible']
                            || $posts[$i]['publish_date'] > date('Y-m-d H:i:s')) { ?>
                            <td>
                                Draft
                            </td>
                    <?php }
                    } ?>
                </tr>
        <?php }
        } ?>
    </table>
</body>

</html>