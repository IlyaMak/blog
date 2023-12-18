<?php
declare(strict_types=1);

include_once('set-project-root.php');
include_once(PROJECT_ROOT . '/src/bootstrap.php');

use App\Service\DatabaseConnector;

function createPostsTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'CREATE TABLE posts (
            id int NOT NULL AUTO_INCREMENT,
            headline varchar(255) NOT NULL,
            body LONGTEXT NOT NULL,
            publish_date datetime NOT NULL,
            image_path varchar(255) NOT NULL,
            is_visible boolean NOT NULL,
            PRIMARY KEY (id)
        );'
    );
}

createPostsTable();
