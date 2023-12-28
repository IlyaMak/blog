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

    public function insertOrUpdatePost(Post $post): int
    {
        $id = $post->getId();
        $headline = $post->getHeadline();
        $body = $post->getBody();
        $publishDate = $post->getPublishDate();
        $imagePath = $post->getImagePath();
        $isVisible = $post->getIsVisible();
        $userId = $post->getUserId();
        $columnsArray = [
            'headline', 'body', 'publish_date', 'image_path', 'is_visible, user_id'
        ];
        $valuesArray = [
            ':headline', ':body', ':publishDate', ':imagePath', ':isVisible, :userId'
        ];
        $columnsAndValuesArray = [
            'headline = :headline',
            'body = :body',
            'publish_date = :publishDate',
            'image_path = :imagePath',
            'is_visible = :isVisible',
            'user_id = :userId',
        ];
        if ($id !== 0) {
            array_unshift($columnsArray, 'id');
            array_unshift($valuesArray, ':id');
            array_unshift($columnsAndValuesArray, 'id = :id');
        }
        $columnsString = implode(',', $columnsArray);
        $valuesString = implode(',', $valuesArray);
        $columnsAndValuesString = implode(',', $columnsAndValuesArray);
        $pdoStatement = $this->db->prepare(
            "INSERT INTO posts ($columnsString) 
            VALUES ($valuesString)
            ON DUPLICATE KEY UPDATE
            $columnsAndValuesString"
        );
        if ($id !== 0) {
            $pdoStatement->bindParam('id', $id, PDO::PARAM_INT);
        }
        $pdoStatement->bindParam('headline', $headline);
        $pdoStatement->bindParam('body', $body);
        $pdoStatement->bindParam('publishDate', $publishDate);
        $pdoStatement->bindParam('imagePath', $imagePath);
        $pdoStatement->bindParam('isVisible', $isVisible, PDO::PARAM_BOOL);
        $pdoStatement->bindParam('userId', $userId, PDO::PARAM_INT);
        $pdoStatement->execute();
        return (int) $this->db->lastInsertId();
    }

    public function getPosts(): array
    {
        $pdoStatement = $this->db->prepare(
            'SELECT p.id, p.headline, p.body, p.publish_date, p.image_path, p.is_visible, p.user_id, COALESCE(t.name, "-") as tags
            FROM posts p 
            LEFT JOIN posts_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON t.id = pt.tag_id'
        );
        $pdoStatement->execute();
        $records = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        $modifiedRecords = [];
        if (is_array($records)) {
            foreach ($records as $record) {
                $id = $record['id'];
                if (!isset($modifiedRecords[$id])) {
                    $modifiedRecords[$id] = $record;
                } else {
                    $modifiedRecords[$id]['tags'] .= ",{$record['tags']}";
                }
            }
        }
        return array_values($modifiedRecords);
    }

    public function getPostById(int $id): array
    {
        $pdoStatement = $this->db->prepare(
            'SELECT p.id, p.headline, p.body, p.publish_date, p.image_path, p.is_visible, p.user_id, t.name as tag_names
            FROM posts p
            LEFT JOIN posts_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON t.id = pt.tag_id
            WHERE p.id = :id'
        );
        $pdoStatement->bindParam('id', $id);
        $pdoStatement->execute();
        $records = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        $modifiedRecords = [];
        if (is_array($records)) {
            $id = null;
            foreach ($records as $record) {
                $id = $record['id'];
                if (!isset($modifiedRecords[$id])) {
                    $modifiedRecords[$id] = $record;
                } else {
                    $modifiedRecords[$id]['tag_names'] .= ",{$record['tag_names']}";
                }
            }
            return $modifiedRecords[$id];
        }
        return $modifiedRecords;
    }

    public function deletePost(int $id): void
    {
        $pdoStatement = $this->db->prepare(
            'DELETE FROM posts WHERE id = :id'
        );
        $pdoStatement->bindParam('id', $id, PDO::PARAM_INT);
        $pdoStatement->execute();
    }
}
