<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use App\Service\DatabaseConnector;
use App\Service\PostService;
use PDO;
use PDOException;
use Throwable;

class PostController
{
    public static function createPost(PDO $db): bool
    {
        $isFailed = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                $_FILES['image']['error'] !== UPLOAD_ERR_OK
                || !in_array($_FILES['image']['type'], ['image/png', 'image/jpg', 'image/jpeg'])
            ) {
                $isFailed = true;
                return $isFailed;
            }
            $post = PostService::getPostInstance();
            if (self::validateCreatePostFields($post, $_FILES['image'])) {
                $postRepository = new PostRepository($db);
                try {
                    $db->beginTransaction();
                    $postId = $postRepository->insertPost($post);
                    if (isset($_POST['tags'])) {
                        $postTagsRepository = new PostsTagsRepository($db);
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
        $isExceptionThrown = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = DatabaseConnector::getDatabaseConnection();
            $postsTagsRepository = new PostsTagsRepository($db);
            $postId = (int) $_POST['id'];
            $postTag = $postsTagsRepository->getPostTag($postId);
            if (is_array($postTag)) {
                $postsTagsRepository->deletePostTag($postId);
            }
            $postRepository = new PostRepository($db);
            try {
                $postRepository->deletePost((int) $_POST['id']);
            } catch (PDOException $e) {
                $isExceptionThrown = true;
            }
            if (!$isExceptionThrown) {
                header('Location: posts-list.php');
            }
        }
        return $isExceptionThrown;
    }

    private static function validateCreatePostFields(Post $post): bool
    {
        return (
            strlen($post->getHeadline()) > 2
            && strlen($post->getBody()) > 2
        );
    }
}
