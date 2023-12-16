<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use App\Service\PostService;
use PDO;
use Throwable;

class PostController
{
    public static function createPost(PDO $db): bool
    {
        $isFailed = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    private static function validateCreatePostFields(
        Post $post, 
        array $image
    ): bool {
        return (
            strlen($post->getHeadline()) > 2
            && strlen($post->getBody()) > 2
            && !empty($image)
            && $image['error'] === UPLOAD_ERR_OK
        );
    }
}
