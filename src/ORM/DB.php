<?php

namespace Src\ORM;

class DB
{
    /**
     * The connection statement
     *
     * @var \PDO
     */
    private $connection;

    /**
     * The table which the query is targeting
     *
     * @var string
     */
    private $from;

    /**
     * The columns to be selected
     *
     * @var string|array
     */
    private $select = '*';

    /**
     * The join clause
     *
     * @var array
     */
    private $join = [];

    /**
     * The where clause
     *
     * @var array
     */
    private $where = [];

    /**
     * Create the instance and set connection
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = $this->connect();
    }

    /**
     * Connect to database
     *
     * @return \PDO
     */
    private function connect(): \PDO
    {
        return new \PDO(
            DB_CONNECTION .
            ':host=' . DB_HOST .
            ';dbname=' . DB_DATABASE,
            DB_USERNAME,
            DB_PASSWORD
        );
    }

    /**
     * Set the columns to be selected
     *
     * @param  string|array $columns
     * @return $this
     */
    public function select($columns): self
    {
        $this->select = $columns;

        return $this;
    }

    /**
     * Set the table which the query is targeting
     *
     * @param  string $table
     * @return $this
     */
    public function from(string $table): self
    {
        $this->from = $table;

        return $this;
    }

    /**
     * Add a where clause to the query
     *
     * @param  string $column
     * @param  string $operator
     * @param  mixed  $column
     * @return $this
     */
    public function where(string $column, string $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [$column, $operator, $value];

        return $this;
    }

    /**
     * Add a join clause to the query
     *
     * @param  string $table
     * @param  string $column_1
     * @param  string $operator
     * @param  string $column_2
     * @return self
     */
    public function join(string $table, string $column_1, string $operator, string $column_2): self
    {
        $this->join[] = [$table, $column_1, $operator, $column_2];

        return $this;
    }

    /**
     * Build the query
     *
     * @param  int $limit
     * @return \PDOStatement
     */
    private function build(int $limit = null): \PDOStatement
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

        $query = "SELECT {$select} FROM {$this->from} {$join} {$where}";

        if ($limit !== null) {
            $query .= "LIMIT {$limit}";
        }

        return $this->connection->query(
            "SELECT {$select} FROM {$this->from} {$join} {$where}"
        );
    }

    /**
     * Get the query result
     *
     * @return array
     */
    public function get(): array
    {
        return $this->build()->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Set query limit to 1 result row and get it.
     *
     * @return \stdClass
     */
    public function first(): \stdClass
    {
        return $this->build(1)->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Begin a query against a database table
     *
     * @param  string $table
     * @return self
     */
    public static function table($table): self
    {
        $instance = new self;
        $instance->from($table)->first();

        return $instance;
    }
}
