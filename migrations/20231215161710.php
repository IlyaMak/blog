<?php

declare(strict_types=1);

include_once(getcwd() . '/src/autoload.php');

use App\Service\DatabaseConnector;

function createPostsTagsTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'CREATE TABLE posts_tags (
            post_id int NOT NULL,
            tag_id int NOT NULL,
            PRIMARY KEY (post_id, tag_id),
            FOREIGN KEY (post_id) REFERENCES posts(id),
            FOREIGN KEY (tag_id) REFERENCES tags(id)
        );'
    );
}

createPostsTagsTable();
