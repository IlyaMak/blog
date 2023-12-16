<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use PDOException;
use DateTime;
use PDO;

class PostController
{
    public static function createPost(PDO $db): bool
    {
        $headline = $_POST['headline'] ?? '';
        $body = '';
        if (isset($_POST['body'])) {
            $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['body']);
        }
        $tags = $_POST['tags'] ?? [];
        $currentYear = isset($_POST['publishDate']) ? (int) strtok($_POST['publishDate'], '-') : 0;
        $publishDate = (isset($_POST['publishDate'])
            && ($currentYear === ((int) date('Y'))
                || $currentYear === ((int) date('Y') + 1)))
            ? DateTime::createFromFormat("Y-m-d\\TH:i", $_POST['publishDate'])
            : new DateTime();
        $imagePath = '';
        if (isset($_FILES['image'])) {
            $imagePath = realpath('../../public') . '/' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }
        $isVisible = (bool) isset($_POST['isVisible']) ?? false;

        $isFailed = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (self::validateCreatePostFields($headline, $body, $_FILES['image'])) {
                $post = new Post(
                    $headline,
                    $body,
                    $tags,
                    $publishDate->format('Y-m-d H:i:s'),
                    $imagePath,
                    $isVisible
                );
                $postRepository = new PostRepository($db);
                try {
                    $db->beginTransaction();
                    $postId = $postRepository->insertPost($post);
                    if (isset($_POST['tags'])) {
                        $postTagsRepository = new PostsTagsRepository($db);
                        $postTagsRepository->insertPostTag($postId, $tags);
                    }
                    $db->commit();
                } catch (PDOException $e) {
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
        string $headline,
        string $body,
        array $image
    ): bool {
        return (
            strlen($headline) > 2
            && strlen($body) > 2
            && isset($image)
            && $image['error'] === UPLOAD_ERR_OK
        );
    }
}
