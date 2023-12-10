<?php

include_once(getcwd() . '/src/index.php');

use Service\DatabaseConnector;

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
