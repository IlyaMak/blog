<?php
declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';

use App\Repository\PostRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$postRepository = new PostRepository($db);
$post = $postRepository->getPostById((int) $_GET['id']);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
</head>

<body>
    <h1>Post</h1>
    <?php if (is_array($post)) { ?>
        <h2><?php echo $post['headline'] ?></h2>
        <img src="<?php echo $post['image_path'] ?>" width="400px" alt="post image">
        <div><?php echo $post['body'] ?></div>
        <?php if ($post['tag_name'] !== null) { ?>
            <div>Tags: <?php echo $post['tag_name'] ?></div>
        <?php } ?>
        <div>Publish date: <?php echo $post['publish_date'] ?></div>
    <?php } ?>
</body>

</html>