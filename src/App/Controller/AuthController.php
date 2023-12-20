<?php

namespace App\Controller;

use App\Entity\User;
use PDOException;
use App\Repository\UserRepository;
use App\Service\DatabaseConnector;

class AuthController
{
    public static function signUp(): bool
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $repeatPassword = $_POST['repeatPassword'] ?? '';
        $isFailedRegistration = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (self::validateSignUpFields($login, $password, $repeatPassword)) {
                $user = new User($login, password_hash($password, PASSWORD_DEFAULT));
                $db = DatabaseConnector::getDatabaseConnection();
                $userRepository = new UserRepository($db);
                try {
                    $userRepository->insertUser($user);
                } catch (PDOException $e) {
                    $isFailedRegistration = true;
                    return $isFailedRegistration;
                }
                header('Location: sign-in.php');
            } else {
                $isFailedRegistration = true;
            }
        }
        return $isFailedRegistration;
    }

    public static function signIn(): bool
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $db = DatabaseConnector::getDatabaseConnection();
        $userRepository = new UserRepository($db);
        $data = $userRepository->getUser($login);
        $isVerified = password_verify($password, $data['password'] ?? '');
        if (is_array($data) && $isVerified) {
            session_start();
            session_regenerate_id();
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['id'] = $data['id'];
        }
        return $isVerified;
    }

    private static function validateSignUpFields(
        string $login,
        string $password,
        string $repeatPassword
    ): bool {
        return (strlen($login) > 2
            && strlen($password) > 5
            && $password === $repeatPassword);
    }
}
