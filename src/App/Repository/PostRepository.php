<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use PDO;

class PostRepository
{
    private ?PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertPost(Post $post): int
    {
        $headline = $post->getHeadline();
        $body = $post->getBody();
        $publishDate = $post->getPublishDate();
        $imagePath = $post->getImagePath();
        $isVisible = $post->getIsVisible();
        $pdoStatement = $this->db->prepare(
            "INSERT INTO posts (headline, body, publish_date, image_path, is_visible) 
            VALUES (:headline, :body, :publishDate, :imagePath, :isVisible);"
        );
        $pdoStatement->bindParam('headline', $headline);
        $pdoStatement->bindParam('body', $body);
        $pdoStatement->bindParam('publishDate', $publishDate);
        $pdoStatement->bindParam('imagePath', $imagePath);
        $pdoStatement->bindParam('isVisible', $isVisible, PDO::PARAM_BOOL);
        $pdoStatement->execute();
        return (int) $this->db->lastInsertId();
    }
}
