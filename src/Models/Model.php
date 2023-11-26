<?php

namespace Models;

use Source\Constant;

class Model
{
    protected static \PDO $pdo;
    protected string $table;
    public function __construct()
    {
        try {
            static::$pdo = new \PDO(
                'mysql:host=mysql;port=3306;dbname=my_database',
                'root',
                'my-secret-pw',
                [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $this->table = strtolower(explode('\\', get_class($this))[1]) . 's';
    }
    public function all(): array
    {
        $statement = $this->getPDO()->query("SELECT * FROM {$this->table}");
        return $statement->fetchAll();
    }


    protected function getPDO(): \PDO
    {
        return static::$pdo;
    }
}
