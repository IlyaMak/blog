<?php

declare(strict_types=1);

include '../set-project-root.php';
include PROJECT_ROOT . '/src/bootstrap.php';
include '../private-page.php';

use App\Controller\PostController;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getTags();
$postId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$post = [];
$postTagIds = [];
$postRepository = new PostRepository($db);
$postTagsRepository = new PostsTagsRepository($db);
if ($postId !== null) {
    $post = $postRepository->getPostById($postId);
    if ($_SESSION['id'] !== $post['user_id']) {
        header('Location: /pages/post/posts-list.php');
        exit;
    }
    $postTagIds = $postTagsRepository->getPostTags($postId);
}
$isFailed = PostController::createPost($db, $post['image_path'] ?? null);
$pageName = $postId === null ? 'Create A Post' : 'Update A Post';
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
    <form action='./create-update-post.php<?= isset($postId) ? "?id=$postId" : '' ?>' method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $postId ?>">
        <div>
            <input type="text" name="headline" placeholder="Headline" value="<?= $post['headline'] ?? '' ?>" required />
        </div>
        <div>
            <textarea name="body" placeholder="Body" required><?= $post['body'] ?? '' ?></textarea>
        </div>
        <div>
            <label for="tags">Select tags</label>
            <select name="tags[]" id="tags" multiple>
                <?php for ($i = 0; $i < count($tags); $i++) { ?>
                    <option value="<?php echo $tags[$i]['id'] ?>" <?= in_array($tags[$i]['id'], $postTagIds) ? 'selected' : '' ?>>
                        <?php echo $tags[$i]['name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="publishDate">Publish Datetime</label>
            <input type="datetime-local" name="publishDate" id="publishDate" value="<?= isset($post['publish_date']) ? substr($post['publish_date'], 0, -3) : '' ?>" />
        </div>
        <div>
            <label for="image">Preview image</label>
            <input type="file" name="image" id="image" size="2000000" <?= isset($postId) ? '' : 'required' ?> />
        </div>
        <div>
            <input type="checkbox" id="isVisible" name="isVisible" <?= (!empty($post) && $post['is_visible'] === 1) || $postId === null ? 'checked' : '' ?> />
            <label for="isVisible">Is visible?</label>
        </div>
        <button type="submit">Save</button>
    </form>
    <?php if ($isFailed) { ?>
        <span>Please, correct fields</span>
    <?php } ?>
</body>

</html>