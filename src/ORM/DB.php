<?php

namespace Src\ORM;

use PDO;

class DB
{
    private $connection;
    private $table_name;

    public function __construct($table_name)
    {
        $this->connection = $this->connect();
        $this->table_name = $table_name;
    }

    private function connect()
    {
        return new PDO(
            DB_CONNECTION .
            ':host=' . DB_HOST .
            ';dbname=' . DB_DATABASE,
            DB_USERNAME,
            DB_PASSWORD
        );
    }

    public static function table($name)
    {
        return new self($name);
    }
}
