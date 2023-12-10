<?php

namespace Repository;

use Entity\Tag;
use Service\DatabaseConnector;
use PDO;

class TagRepository
{
    private ?PDO $db;

    public function __construct($db)
    {
        $db = DatabaseConnector::getDatabaseConnection();
        $this->db = $db;
    }

    public function insertTag(Tag $tag): void
    {
        $name = $tag->getName();
        $isVisible = $tag->getIsVisible();
        $parentTag = $tag->getParentTag();
        $pdoStatement = $this->db->prepare(
            "INSERT INTO tags (name, is_visible, parent_tag) 
            VALUES (:name, :isVisible, :parentTag);"
        );
        $pdoStatement->bindParam('name', $name);
        $pdoStatement->bindParam('isVisible', $isVisible, PDO::PARAM_BOOL);
        $pdoStatement->bindParam('parentTag', $parentTag, PDO::PARAM_INT);
        $pdoStatement->execute();
    }

    public function getTags(): array|bool
    {
        $pdoStatement = $this->db->prepare("SELECT * FROM tags");
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}
