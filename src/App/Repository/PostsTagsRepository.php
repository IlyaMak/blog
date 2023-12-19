<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

class PostsTagsRepository
{
    private ?PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertPostTag(int $postId, array $tags): void
    {
        $postTagInsertQueries = [];
        for ($i = 0; $i <= count($tags) - 1; $i++) {
            $postTagInsertQueries[] = "($postId, {$tags[$i]})";
        }
        $sqlValues = implode(',', $postTagInsertQueries);
        $sql = "INSERT INTO posts_tags (post_id, tag_id) VALUES $sqlValues";
        $pdoStatement = $this->db->prepare("$sql;");
        $pdoStatement->execute();
    }

    public function deletePostTags(int $postId): void
    {
        $pdoStatement = $this->db->prepare(
            'DELETE FROM posts_tags WHERE post_id = :postId'
        );
        $pdoStatement->bindParam('postId', $postId, PDO::PARAM_INT);
        $pdoStatement->execute();
    }
}
