<?php
declare(strict_types=1);

include_once('set-project-root.php');
include_once(PROJECT_ROOT . '/src/bootstrap.php');

use App\Service\DatabaseConnector;

function createTagsTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'CREATE TABLE tags (
            id int NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL UNIQUE,
            is_visible boolean NOT NULL,
            parent_tag_id int NULL DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (parent_tag_id) REFERENCES tags(id)
        );'
    );
}

createTagsTable();
