<?php

namespace App\DB;

use Exception;
use PDO;
use PDOException;

class Db extends PDO
{
    private const DB_HOST = "mvc_debut_exo-db-1";
    private const DB_NAME = "demo_mvc";
    private const DB_USER = "root";
    private const DB_PASSWORD = "root";
    private static ?Db $instance = null;

    public function __construct()
    {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ';charset=utf8mb4';

            parent::__construct($dsn, self::DB_USER, self::DB_PASSWORD,);

            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8mb4");
        } catch (PDOException $error) {
            throw new Exception($error->getMessage());
        }
    }
    public static function getInstance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }
}
