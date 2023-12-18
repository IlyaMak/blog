<?php
declare(strict_types=1);

include_once('set-project-root.php');
include_once(PROJECT_ROOT . '/src/bootstrap.php');

use App\Service\DatabaseConnector;

function createUsersTable()
{
    $db = DatabaseConnector::getDatabaseConnection();
    $db->query(
        'CREATE TABLE users (
            id int NOT NULL AUTO_INCREMENT,
            login varchar(255) NOT NULL UNIQUE,
            password varchar(255) NOT NULL,
            PRIMARY KEY (id)
        );'
    );
}

createUsersTable();
