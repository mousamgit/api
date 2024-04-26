<?php

namespace config;

class DB
{
    protected static $pdo;

    public static function connect()
    {
        $config = require_once __DIR__ . '/../config/database.php';


        try {
            self::$pdo = new \PDO(
                $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['username'],
                $config['password']
            );

            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function select($query)
    {
        self::connect();
        $stmt = self::$pdo->query($query);
        return $stmt->fetchAll();
    }
}
