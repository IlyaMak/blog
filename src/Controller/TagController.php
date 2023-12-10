<?php

namespace Controller;

use Entity\Tag;
use PDOException;
use Repository\TagRepository;

class TagController
{
    public static function createTag(): bool
    {
        $name = $_POST['name'] ?? '';
        $isVisible = $_POST['isVisible'] ?? null;
        $parentTag = isset($_POST['parentTag']) ?
            ($_POST['parentTag'] === '' ? null : $_POST['parentTag']) : null;
        $isFailed = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (self::validateCreateTagFields($name, $isVisible)) {
                $tag = new Tag($name, $isVisible, $parentTag);
                $tagRepository = new TagRepository();
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

    private static function validateCreateTagFields(
        string $name,
        ?bool $isVisible
    ): bool {
        return strlen($name) > 1 && isset($isVisible);
    }
}
