<?php

namespace Src\Database;

class QueryBuilder
{
    /**
     * The connection statement
     *
     * @var \PDO
     */
    private static $connection;

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
     * The limit clause
     *
     * @var array
     */
    private $limit = null;

    /**
     * The order clause
     *
     * @var array
     */
    private $order = [];

    /**
     * The generated SQL query
     * 
     * @var string
     */
    private $sql;

    /**
     * The where clause
     *
     * @var array
     */
    private $where = [];

    /**
     * Connect to database
     *
     * @return \PDO
     */
    private static function connect(): \PDO
    {
        $env = [
            'connection' => getenv('DB_CONNECTION'),
            'database' => getenv('DB_DATABASE'),
            'host' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD')
        ];

        if ($env['connection'] === 'sqlite') {
            return new \PDO("{$env['connection']}:{$env['database']}");
        }

        return new \PDO(
            "{$env['connection']}:host={$env['host']};dbname={$env['database']}",
            $env['username'],
            $env['password']
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

        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

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
    public function join(string $table, string $column_1, string $operator, string $column_2 = null): self
    {
        if ($column_2 === null) {
            $column_2 = $operator;
            $operator = '=';
        }

        $this->join[] = [
            'table' => $table,
            'column_1' => $column_1,
            'operator' => $operator,
            'column_2' => $column_2
        ];

        return $this;
    }

    /**
     * Build the SQL query
     *
     * @return void
     */
    private function build(): void
    {
        $select = $this->select;
        $instructionsArr = [];
        $order = '';
        $where = '';

        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        $join = array_map(function ($parameters) {
            return "JOIN {$parameters['table']} ON {$parameters['column_1']} {$parameters['operator']} {$parameters['column_2']}";
        }, $this->join);

        if ($join) {
            $instructionsArr[] = implode(' ', $join);
        }

        foreach ($this->where as $key => $parameters) {
            $condition = "{$parameters['column']} {$parameters['operator']} '{$parameters['value']}'";

            $where .= (! $key ? 'WHERE ' : ' AND ') . $condition;
        }

        if ($where) {
            $instructionsArr[] = $where;
        }

        if ($this->order) {
            $instructionsArr[] = "ORDER BY {$this->order['column']} {$this->order['direction']}";
        }

        if ($this->limit !== null) {
            $instructionsArr[] = "LIMIT {$this->limit}";
        }
        
        $instructions = implode(' ', $instructionsArr);

        $query = "SELECT {$select} FROM {$this->from} {$instructions}";

        $this->sql = trim($query);
    }

    /**
     * Run the SQL query
     * 
     * @return \PDOStatement|bool
     */
    private function run()
    {
        return self::getConnection()->query($this->sql);
    }

    /**
     * Get the query result
     *
     * @return array
     */
    public function get(): array
    {
        $this->build();

        return $this->run()->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get the SQL query
     *
     * @return string
     */
    public function getSql(): string
    {
        $this->build();

        return $this->sql;
    }

    public static function getConnection(): \PDO
    {
        if (! isset(self::$connection)) {
            self::$connection = self::connect();
        }

        return self::$connection;
    }

    /**
     * Set query limit to 1 result row and get it.
     *
     * @return \stdClass|bool
     */
    public function first()
    {
        $this->limit(1);
        $this->build();

        return $this->run()->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Set query limit.
     *
     * @param  int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set order by a specific column.
     * 
     * @param  string $column
     * @param  string $order
     * @return self
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->order = [
            'column' => $column,
            'direction' => strtoupper($direction)
        ];

        return $this;
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
        $instance->from($table);

        return $instance;
    }
}
