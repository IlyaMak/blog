<?php
declare(strict_types=1);

namespace App\Service;

use PDO;
use PDOException;

class DatabaseConnector
{
    private static $db;

    private function __construct()
    {
    }

    public static function getDatabaseConnection(): PDO
    {
        if (self::$db == null) {
            try {
                self::$db = new PDO(
                    "mysql:host={$_ENV['DATABASE_SERVER_NAME']};dbname={$_ENV['DATABASE_NAME']}",
                    $_ENV['DATABASE_USER'],
                    $_ENV['DATABASE_PASSWORD']
                );
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$db;
    }
}
