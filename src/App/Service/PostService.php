<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use DateTime;

class PostService
{
    public static function getPostInstance(): Post
    {
        $headline = $_POST['headline'];
        $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['body']);
        $tags = $_POST['tags'] ?? [];
        $publishDate = strlen($_POST['publishDate']) > 0
            ? DateTime::createFromFormat("Y-m-d\\TH:i", $_POST['publishDate'])
            : new DateTime();
        $imagePath = '';
        $imagePath = '/public/' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $isVisible = !empty($_POST['isVisible']);
        return new Post(
            $headline,
            $body,
            $tags,
            $publishDate->format('Y-m-d H:i:s'),
            $imagePath,
            $isVisible
        );
    }
}
