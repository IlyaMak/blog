<?php

declare(strict_types=1);

include_once('set-project-root.php');
include_once(PROJECT_ROOT . '/src/bootstrap.php');

use App\Service\DatabaseConnector;

function addUserIdToPostsTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'ALTER TABLE posts
        ADD COLUMN user_id INT NOT NULL,
        ADD FOREIGN KEY (user_id) REFERENCES users(id);'
    );
}

addUserIdToPostsTable();
