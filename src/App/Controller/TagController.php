<?php

namespace App\Controller;

use App\Entity\Tag;
use PDOException;
use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

class TagController
{
    public static function createTag(): bool
    {
        $name = $_POST['name'] ?? '';
        $isVisible = $_POST['isVisible'] ?? false;
        $parentTagId = empty($_POST['parentTagId']) ? null : $_POST['parentTagId'];

        $isFailed = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (self::validateCreateTagFields($name, $isVisible)) {
                $tag = new Tag($name, $isVisible, $parentTagId);
                $db = DatabaseConnector::getDatabaseConnection();
                $tagRepository = new TagRepository($db);
                try {
                    $tagRepository->insertTag($tag);
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

    private static function validateCreateTagFields(
        string $name,
        ?bool $isVisible
    ): bool {
        return strlen($name) > 1 && isset($isVisible);
    }
}
