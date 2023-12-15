<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostsTagsRepository;
use PDOException;
use App\Service\DatabaseConnector;
use DateTime;

class PostController
{
    public static function createPost(): bool
    {
        $headline = $_POST['headline'] ?? '';
        $body = $_POST['body'] ?? '';
        $tags = $_POST['tags'] ?? [];
        $currentYear = isset($_POST['publishDate']) ? (int) strtok($_POST['publishDate'], '-') : 0;
        $publishDate = (isset($_POST['publishDate'])
            && ($currentYear === ((int) date('Y'))
                || $currentYear === ((int) date('Y') + 1)))
            ? new DateTime(
                strtok($_POST['publishDate'], 'T') . ' ' . substr($_POST['publishDate'], (int) strpos($_POST['publishDate'], 'T') + 1)
            )
            : new DateTime();
        $imagePath = '';
        if (isset($_FILES['image'])) {
            $imagePath = realpath('../../public') . '/' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }
        $isVisible = (bool) isset($_POST['isVisible']) ?? false;

        $isFailed = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (self::validateCreatePostFields($headline, $body)) {
                $post = new Post(
                    $headline,
                    $body,
                    $tags,
                    $publishDate->format('Y-m-d H:i:s'),
                    $imagePath,
                    $isVisible
                );
                $db = DatabaseConnector::getDatabaseConnection();
                $postRepository = new PostRepository($db);
                try {
                    $postId = $postRepository->insertPost($post);
                    if (isset($_POST['tags'])) {
                        $postTagsRepository = new PostsTagsRepository($db);
                        $postTagsRepository->insertPostTag($postId, $tags);
                    }
                } catch (PDOException $e) {
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
        string $body
    ): bool {
        return (
            strlen($headline) > 2 
            && strlen($body) > 2 
            && isset($_FILES['image'])
            && $_FILES['image']['error'] === UPLOAD_ERR_OK
        );
    }
}
