<?php

declare(strict_types=1);

include '../../src/autoload.php';

use App\Repository\PostRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$postRepository = new PostRepository($db);
$posts = $postRepository->getVisiblePosts();
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
    <a href="./create-post.php">Create A Post</a>
    <a href="../tag/tags-list.php">Tags List</a>
    <table>
        <tr>
            <th>Image</th>
            <th>Headline</th>
            <th>Body</th>
            <th>Tags</th>
        </tr>
        <?php if (count($posts) > 0) {
            for ($i = 0; $i <= count($posts) - 1; $i++) { ?>
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
                </tr>
        <?php }
        } ?>
    </table>
</body>

</html>