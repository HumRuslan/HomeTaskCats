<?php
namespace core;
use \PDO;

class ConnectDB
{
    private static $connectDB = null;

    public static function connectDB()
    {
        if (!self::$connectDB) {
            try {
                self::$connectDB = new PDO(
                    sprintf("mysql::host=%s; dbname=%s", SERVER_NAME, DB_NAME), //форматування стрічки
                    USER_NAME,
                    PASSWORD
                );
                self::$connectDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connected failed: " . $e->getMessage());
            }
        }
        return self::$connectDB;
    }

    private function __construct(){}

    private function __clone(){}

    public function __destruct()
    {
        self::$connectDB = null;
    }
}