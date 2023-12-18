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

    public function getVisiblePosts(): array|bool
    {
        $visible = true;
        $currentDate = date('Y-m-d H:i:s');
        $pdoStatement = $this->db->prepare(
            'SELECT p.id, p.headline, p.body, p.publish_date, p.image_path, COALESCE(t.name, "-") as tags
            FROM posts p 
            LEFT JOIN posts_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON t.id = pt.tag_id
            WHERE p.is_visible = :visible AND p.publish_date <= :currentDate'
        );
        $pdoStatement->bindParam('visible', $visible, PDO::PARAM_BOOL);
        $pdoStatement->bindParam('currentDate', $currentDate);
        $pdoStatement->execute();
        $records = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        $modifiedRecords = [];
        foreach ($records as $record) {
            $id = $record['id'];
            if (!isset($modifiedRecords[$id])) {
                $modifiedRecords[$id] = $record;
            } else {
                $modifiedRecords[$id]['tags'] .= ",{$record['tags']}";
            }
        }
        return array_values($modifiedRecords);
    }
}
