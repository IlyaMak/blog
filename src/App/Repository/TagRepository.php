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
        if ($id !== 0) {
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
        if ($id !== 0) {
            $pdoStatement->bindParam('id', $id, PDO::PARAM_INT);
        }
        $pdoStatement->bindParam('name', $name);
        $pdoStatement->bindParam('isVisible', $isVisible, PDO::PARAM_BOOL);
        $pdoStatement->bindParam('parentTagId', $parentTagId, PDO::PARAM_INT);
        $pdoStatement->execute();
    }

    public function getTags(): array
    {
        $pdoStatement = $this->db->prepare("SELECT * FROM tags");
        $pdoStatement->execute();
        $fetchedResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return is_array($fetchedResult) ? $fetchedResult : [];
    }

    public function getVisibleTagsWithParentTagName(): int
    {
        $pdoStatement = $this->db->prepare(
            "SELECT t1.id, t1.name, COALESCE(t2.name, '-') as parent_tag_name FROM tags t1
            LEFT JOIN tags t2 on t1.parent_tag_id = t2.id
            WHERE t1.is_visible = 1"
        );
        $pdoStatement->execute();
        return $pdoStatement->rowCount();
    }

    public function getLimitedVisibleTagsWithParentTagName(
        int $offset,
        int $limit
    ): array|bool {
        $pdoStatement = $this->db->prepare(
            "SELECT t1.id, t1.name, COALESCE(t2.name, '-') as parent_tag_name FROM tags t1
            LEFT JOIN tags t2 on t1.parent_tag_id = t2.id
            WHERE t1.is_visible = 1
            LIMIT :limit OFFSET :offset"
        );
        $pdoStatement->bindParam('limit', $limit, PDO::PARAM_INT);
        $pdoStatement->bindParam('offset', $offset, PDO::PARAM_INT);
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

    public function getTag(int $id): array|null
    {
        $pdoStatement = $this->db->prepare(
            "SELECT t1.id, t1.name, t1.is_visible, COALESCE(t2.id, NULL) AS parent_tag_id, COALESCE(t2.name, NULL) AS parent_tag_name
            FROM tags t1
            LEFT JOIN tags t2 ON t1.parent_tag_id = t2.id
            WHERE t1.id = :id"
        );
        $pdoStatement->bindParam('id', $id);
        $pdoStatement->execute();
        $fetchedResult = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return is_array($fetchedResult) ? $fetchedResult : null;
    }
}
