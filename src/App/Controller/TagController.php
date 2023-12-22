<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
use PDOException;
use App\Repository\TagRepository;
use App\Service\DatabaseConnector;
use PDO;

class TagController
{
    public static function createOrUpdateTag(PDO $db): ?bool
    {
        $isFailed = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['id'] ?? null;
            $name = trim($_POST['name']) ?? '';
            $isVisible = (bool) $_POST['isVisible'] ?? false;
            $parentTagId = empty($_POST['parentTagId']) ? null : $_POST['parentTagId'];

            if (self::validateCreateTagFields($name)) {
                $tag = new Tag($id, $name, $isVisible, $parentTagId);
                $tagRepository = new TagRepository($db);
                try {
                    $tagRepository->insertOrUpdateTag($tag);
                } catch (PDOException $e) {
                    $isFailed = true;
                    return $isFailed;
                }
                header('Location: tags-list.php');
            } else {
                $isFailed = true;
            }
        }
        return $isFailed;
    }

    public static function deleteTag(): bool
    {
        $isExceptionThrown = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = DatabaseConnector::getDatabaseConnection();
            $tagRepository = new TagRepository($db);
            try {
                $tagRepository->deleteTag($_POST['tagId']);
            } catch (PDOException $e) {
                $isExceptionThrown = true;
            }
            if (!$isExceptionThrown) {
                header('Location: tags-list.php');
            }
        }
        return $isExceptionThrown;
    }

    private static function validateCreateTagFields(string $name): bool {
        return strlen($name) > 1;
    }
}
