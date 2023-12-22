<?php

declare(strict_types=1);

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

    public function insertOrUpdateTag(Tag $tag): void
    {
        $name = $tag->getName();
        $id = $tag->getId();
        $isVisible = $tag->getIsVisible();
        $parentTagId = $tag->getParentTagId();
        $columnsArray = ['name', ' is_visible', ' parent_tag_id'];
        $valuesArray = [':name', ' :isVisible', ' :parentTagId'];
        $columnsAndValuesArray = ['name = :name', ' is_visible = :isVisible', ' parent_tag_id = :parentTagId'];
        if ($id !== null) {
            array_unshift($columnsArray, 'id');
            array_unshift($valuesArray, ':id');
            array_unshift($columnsAndValuesArray, 'id = :id');
        }
        $columnsString = implode(',', $columnsArray);
        $valuesString = implode(',', $valuesArray);
        $columnsAndValuesString = implode(',', $columnsAndValuesArray);
        $pdoStatement = $this->db->prepare(
            "INSERT INTO tags ($columnsString)
            VALUES ($valuesString)
            ON DUPLICATE KEY UPDATE
            $columnsAndValuesString"
        );
        if ($id !== null) {
            $pdoStatement->bindParam('id', $id, PDO::PARAM_INT);
        }
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

    public function getTag(int $id): array|bool
    {
        $pdoStatement = $this->db->prepare(
            "SELECT t1.id, t1.name, t1.is_visible, COALESCE(t2.id, NULL) AS parent_tag_id, COALESCE(t2.name, NULL) AS parent_tag_name
            FROM tags t1
            LEFT JOIN tags t2 ON t1.parent_tag_id = t2.id
            WHERE t1.id = :id"
        );
        $pdoStatement->bindParam('id', $id);
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTag(int $id, array $tag): void
    {
        $pdoStatement = $this->db->prepare(
            "UPDATE tags
            SET name = :name, is_visible = :isVisible, parent_tag_id = :parent_tag_id
            WHERE id = :id"
        );
        $pdoStatement->bindParam('id', $id);
        $pdoStatement->bindParam('name', $tag['name']);
        $pdoStatement->bindParam('isVisible', $tag['isVisible']);
        $pdoStatement->bindParam('parentTagId', $tag['parentTagId']);
        $pdoStatement->execute();
    }
}
