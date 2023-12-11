<?php

namespace Repository;

use Entity\Tag;
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
}
