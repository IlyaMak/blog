<?php

namespace App\Repository;

use App\Service\DatabaseConnector;
use App\Entity\User;
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
        $login = $user->getLogin();
        $password = $user->getPassword();
        $pdoStatement = $this->db->prepare(
            "INSERT INTO users (login, password) VALUES (:login, :password);"
        );
        $pdoStatement->bindParam('login', $login);
        $pdoStatement->bindParam('password', $password);
        $pdoStatement->execute();
    }

    public function getUser(string $login): array|bool
    {
        $pdoStatement = $this->db->prepare(
            "SELECT * FROM users WHERE login = :login;"
        );
        $pdoStatement->bindParam('login', $login);
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }
}
