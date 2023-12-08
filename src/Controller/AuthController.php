<?php

namespace Controller;

use Entity\User;
use Repository\UserRepository;
use Service\DatabaseConnector;

class AuthController
{
    public static function signUp(): bool
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $repeatPassword = $_POST['repeatPassword'] ?? '';
        $isFailedRegistration = false;

        if (
            $_SERVER['REQUEST_METHOD'] == 'POST'
            && (isset($login) && strlen($login > 2))
            && (isset($password) && strlen($password) > 5)
            && isset($repeatPassword)
            && $password === $repeatPassword
        ) {
            $user = new User($login, password_hash($password, PASSWORD_DEFAULT));
            $db = DatabaseConnector::getDatabaseConnection();
            $userRepository = new UserRepository($db);
            $userRepository->insertUser($user);
            $db = null;
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST'
            && (!(isset($login) && strlen($login > 2))
                || !(isset($password) && strlen($password) > 5)
                || !isset($repeatPassword)
                || !($password === $repeatPassword))
        ) {
            $isFailedRegistration = true;
        }
        return $isFailedRegistration;
    }
}
