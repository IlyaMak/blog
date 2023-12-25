<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use DateTime;

class PostService
{
    public static function getPostInstance(?string $oldImagePath): Post
    {
        $id = (int) $_POST['id'] ?? 0;
        $headline = $_POST['headline'];
        $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['body']);
        $tags = $_POST['tags'] ?? [];
        $date = DateTime::createFromFormat(
            "Y-m-d\\TH:i",
            $_POST['publishDate']
        );
        $publishDate = $date === false ? new DateTime() : $date;
        $dateTime = new DateTime();
        $dateTimeFormat = $dateTime->format('YmdHisv');
        $imagePath = "/public/$dateTimeFormat." .
            substr($_FILES['image']['type'], strpos($_FILES['image']['type'], '/') + 1);
        if ($id === 0) {
            move_uploaded_file($_FILES['image']['tmp_name'], PROJECT_ROOT . $imagePath);
        } else {
            $imagePath = $oldImagePath;
            if (strlen($_FILES['image']['name']) > 0) {
                $imagePath = "/public/$dateTimeFormat." .
                    substr($_FILES['image']['type'], strpos($_FILES['image']['type'], '/') + 1);
                move_uploaded_file($_FILES['image']['tmp_name'], PROJECT_ROOT . $imagePath);
                unlink(PROJECT_ROOT . $oldImagePath);
            }
        }
        $isVisible = !empty($_POST['isVisible']);
        return new Post(
            $id,
            $headline,
            $body,
            $tags,
            $publishDate->format('Y-m-d H:i:s'),
            $imagePath,
            $isVisible,
            $_SESSION['id']
        );
    }
}
