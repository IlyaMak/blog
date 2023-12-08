<?php

namespace Service;

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
                $env = parse_ini_file('../../.env');
                self::$db = new PDO(
                    "mysql:host={$env['DATABASE_SERVER_NAME']};dbname={$env['DATABASE_NAME']}",
                    $env['DATABASE_USER'],
                    $env['DATABASE_PASSWORD']
                );
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$db;
    }
}
