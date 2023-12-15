<?php

declare(strict_types=1);

include_once(getcwd() . '/src/autoload.php');

use App\Service\DatabaseConnector;

function createPostsTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'CREATE TABLE posts (
            id int NOT NULL AUTO_INCREMENT,
            headline varchar(255) NOT NULL,
            body varchar(255) NOT NULL,
            publish_date varchar(255) NOT NULL,
            image_path varchar(255) NOT NULL,
            is_visible boolean NOT NULL,
            PRIMARY KEY (id)
        );'
    );
}

createPostsTable();
