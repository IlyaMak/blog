<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use App\Service\DatabaseConnector;
use App\Service\PostService;
use PDO;
use Throwable;

class PostController
{
    public static function createPost(PDO $db, ?string $oldImagePath): bool
    {
        $isFailed = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                ($_FILES['image']['error'] !== UPLOAD_ERR_OK
                    || !in_array($_FILES['image']['type'], ['image/png', 'image/jpg', 'image/jpeg']))
                && $oldImagePath === null
            ) {
                $isFailed = true;
                return $isFailed;
            }
            $post = PostService::getPostInstance($oldImagePath);
            if (self::validateCreatePostFields($post)) {
                $postRepository = new PostRepository($db);
                try {
                    $db->beginTransaction();
                    $postId = $postRepository->insertOrUpdatePost($post);
                    $postTagsRepository = new PostsTagsRepository($db);
                    if ($_POST['id'] !== '') {
                        $postId = (int) $_POST['id'];
                        $postTagsRepository->deletePostTags($postId);
                    }
                    if (isset($_POST['tags'])) {
                        $postTagsRepository->insertPostTag($postId, $_POST['tags']);
                    }
                    $db->commit();
                } catch (Throwable $e) {
                    $db->rollBack();
                    $isFailed = true;
                    return $isFailed;
                }
                header('Location: posts-list.php');
            } else {
                $isFailed = true;
            }
        }
        return $isFailed;
    }

    public static function deletePost(): bool
    {
        $isFailed = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = DatabaseConnector::getDatabaseConnection();
            $postsTagsRepository = new PostsTagsRepository($db);
            $postRepository = new PostRepository($db);
            try {
                $db->beginTransaction();
                $postId = (int) $_POST['id'];
                $postsTagsRepository->deletePostTags($postId);
                $postRepository->deletePost($postId);
                $db->commit();
            } catch (Throwable $e) {
                $db->rollBack();
                $isFailed = true;
            }
            if (!$isFailed) {
                header('Location: posts-list.php');
            }
        }
        return $isFailed;
    }

    private static function validateCreatePostFields(Post $post): bool
    {
        return (
            strlen($post->getHeadline()) > 2
            && strlen($post->getBody()) > 2
        );
    }
}
