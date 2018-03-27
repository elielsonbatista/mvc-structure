<?php

namespace Src\ORM;

use PDO;

class DB
{
    private $connection;
    private $table;
    private $select = '*';
    private $join = [];
    private $where = [];

    public function __construct($table)
    {
        $this->connection = $this->connect();
        $this->table = $table;
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

    public function select($fields)
    {
        $this->select = $fields;
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [$column, $operator, $value];
        return $this;
    }

    public function join($table, $column_1, $operator, $column_2) {
        $this->join[] = [$table, $column_1, $operator, $column_2];
        return $this;
    }

    private function build($limit = null)
    {
        $select = $this->select;
        $join = '';
        $where = '';

        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        foreach ($this->join as $key => $value) {
            $join .= " JOIN {$value[0]} ON {$value[1]} {$value[2]} {$value[3]}";
        }

        foreach ($this->where as $key => $value) {
            if (!$key) {
                $where = "WHERE {$value[0]} {$value[1]} '{$value[2]}'";
            } else {
                $where .= " AND {$value[0]} {$value[1]} '{$value[2]}'";
            }
        }

        $query = "SELECT {$select} FROM {$this->table} {$join} {$where}";

        if ($limit !== null) {
            $query .= "LIMIT {$limit}";
        }

        return $this->connection->query(
            "SELECT {$select} FROM {$this->table} {$join} {$where}"
        );
    }

    public function get()
    {
        return $this->build()->fetchAll(PDO::FETCH_OBJ);
    }

    public function first()
    {
        return $this->build(1)->fetch(PDO::FETCH_OBJ);
    }

    public static function table($name)
    {
        return new self($name);
    }
}
