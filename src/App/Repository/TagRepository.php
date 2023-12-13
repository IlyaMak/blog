<?php

namespace App\Repository;

use App\Entity\Tag;
use PDO;

class TagRepository
{
    private ?PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertTag(Tag $tag): void
    {
        $name = $tag->getName();
        $isVisible = $tag->getIsVisible();
        $parentTagId = $tag->getParentTagId();
        $pdoStatement = $this->db->prepare(
            "INSERT INTO tags (name, is_visible, parent_tag_id) 
            VALUES (:name, :isVisible, :parentTagId);"
        );
        $pdoStatement->bindParam('name', $name);
        $pdoStatement->bindParam('isVisible', $isVisible, PDO::PARAM_BOOL);
        $pdoStatement->bindParam('parentTagId', $parentTagId, PDO::PARAM_INT);
        $pdoStatement->execute();
    }

    public function getTags(): array|bool
    {
        $pdoStatement = $this->db->prepare("SELECT * FROM tags");
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVisibleTagsWithParentTagName(): array|bool
    {
        $pdoStatement = $this->db->prepare(
            "SELECT t1.id, t1.name, COALESCE(t2.name, '-') as parent_tag_name FROM tags t1
            LEFT JOIN tags t2 on t1.parent_tag_id = t2.id
            WHERE t1.is_visible = 1"
        );
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTag(int $id): void
    {
        $pdoStatement = $this->db->prepare(
            "DELETE FROM tags WHERE id = :id;"
        );
        $pdoStatement->bindParam('id', $id);
        $pdoStatement->execute();
    }
}
