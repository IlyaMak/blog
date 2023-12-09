<?php

namespace Repository;

use Service\DatabaseConnector;
use Entity\User;
use PDO;

class UserRepository
{
    private ?PDO $db;

    public function __construct($db)
    {
        $db = DatabaseConnector::getDatabaseConnection();
        $this->db = $db;
    }

    public function insertUser(User $user)
    {
        $this->db->query(
            "INSERT INTO users (login, password)
            VALUES ('{$user->getLogin()}', '{$user->getPassword()}');"
        );
        $this->db = null;
    }

    public function getUser(string $login): array|bool
    {
        $pdoStatement = $this->db->query(
            "SELECT * FROM users WHERE login = '$login';"
        );
        $this->db = null;
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }
}
