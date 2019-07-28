<?php
namespace System;
use PDO;
use PDOException;
class Database {
    //This class is fully responsible for handling all queries on database
    // return $this; in class methods is used for 'Method Chaining'.

    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * PDO Connection
     *
     * @var \PDO
     */
    private static $connection;

    /**
     * Table Name
     *
     * @var string
     */
    private $table;

    /**
     * Data Container
     *
     * @var array
     */
    private $data = [];//Data of INSERT or UPDATE

    /**
     * Bindings Container
     *
     * @var array
     */
    private $bindings = [];

    /**
     * Last Insert Id
     *
     * @var int
     */
    private $lastId;

    /**
     * Wheres
     *
     * @var array
     */
    private $wheres = [];

    /**
     * Havings
     *
     * @var array
     */
    private $havings = [];

    /**
     * Group By
     *
     * @var array
     */
    private $groupBy = [];

    /**
     * Selects
     *
     * @var array
     */
    private $selects = [];

    /**
     * Limit
     *
     * @var int
     */
    private $limit;

    /**
     * Offset
     *
     * @var int
     */
    private $offset;

    /**
     * Total Rows
     *
     * @var int
     */
    private $rows = 0;

    /**
     * Joins
     *
     * @var array
     */
    private $joins = [];

    /**
     * Order By
     *
     * @array
     */
    private $orerBy = [];

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->isConnected()) {
            $this->connect();
        }
    }

    /**
     * Determine if there is any connection to database
     *
     * @return bool
     */
    private function isConnected() {//We have to ways to check:
        //return !is_null(static::$connection);//First way
        //Second way
        return static::$connection instanceof PDO;//Check if the     private static $connection     is an instance of the PDO Class or not
    }

    /**
     * Connect To Database
     *
     * @return void
     */
    private function connect() {
        $connectionData = $this->app->file->call('config.php');
        //pre($connectionData);
        extract($connectionData);
        try {
            //static::$connection = new PDO ('mysql:host='. $connectionData['server'] . ';dbname=' . $connectionData['dbname'], $connectionData['dbuser'], $connectionData['dbpass']);
            static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);
            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//Makes the default form of the returned values from queries as OBJECTS
            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Make the Errors as Exception (the default error is silent)
            static::$connection->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        //echo $this->isConnected() . '<br>';
    }

    /**
     * Get Database Connection Object PDO Object
     *
     * @return \PDO
     */
    public function connection()
    {
        return static::$connection;
    }

    /**
     * Set select clause
     *
     * @return $this
     */
    public function select(...$selects)
    {
        // for those who use PHP 5.6
        // you can use the ... operator

        // otherwise , use the following line to get all passed arguments
        $selects = func_get_args();

        $this->selects = array_merge($this->selects, $selects);

        return $this;//'Method Chaining'
    }

    /**
     * Set Join clause
     *
     * @param string $join
     * @return $this
     */
    public function join($join)
    {
        $this->joins[] = $join;

        return $this;//'Method Chaining'
    }

    /**
     * Set Limit and offset
     *
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0) {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;//'Method Chaining'
    }

    /**
     * Set Order By clause
     *
     * @param string $column
     * @param string $sort
     * @return $this
     */
    public function orderBy($orderBy, $sort = 'ASC') {
        $this->orerBy = [$orderBy, $sort];
        return $this;//'Method Chaining'
    }

    /**
     * Fetch Table
     * This will return only one record
     *
     * @param string $table
     * @return \stdClass | null
     */
    public function fetch($table = null) {
        if ($table) {
            $this->table($table);
        }
        $sql = $this->fetchStatement();
        $result = $this->query($sql, $this->bindings)->fetch();
        $this->reset();
        return $result;
    }

    /**
     * Fetch All Records from Table
     *
     * @param string $table
     * @return array
     */
    public function fetchAll($table = null) {
        if ($table) {
            $this->table($table);
        }
        $sql = $this->fetchStatement();
        $query = $this->query($sql, $this->bindings);
        $results = $query->fetchAll();
        $this->rows = $query->rowCount();
        $this->reset();
        //pre($this->wheres);
        return $results;
    }

    /**
     * Get total rows from last fetch all statement
     *
     * @return int
     */
    public function rows() {
        return $this->rows;
    }

    /**
     * Prepare Select Statement
     *
     * @return string
     */
    private function fetchStatement() {
        $sql = 'SELECT ';
        if ($this->selects) {
            //pre($this->selects);
            //pre(implode(',' , $this->selects));
            $sql .= implode(',' , $this->selects);
        } else {
            $sql .= '*';
        }
        $sql .= ' FROM ' . $this->table . ' ';
        if ($this->joins) {
            //pre($this->joins);
            //pre(implode(',' , $this->joins));
            $sql .= implode(' ' , $this->joins);
        }
        if ($this->wheres) {
            //pre($this->wheres);
            //pre(implode(',' , $this->wheres));
            $sql .= ' WHERE ' . implode(' ', $this->wheres) . ' ';
        }
        if ($this->havings) {
            //pre($this->havings);
            //pre(implode(',' , $this->havings));
            $sql .= ' HAVING ' . implode(' ', $this->havings) . ' ';
        }
        if ($this->orerBy) {
            //pre($this->orerBy);
            //echo '<pre>', var_dump(implode(',' , $this->orerBy)), '<pre>';
            $sql .= ' ORDER BY ' . implode(' ' , $this->orerBy);
        }
        if ($this->limit) {
            //pre($this->limit);
            $sql .= ' LIMIT ' . $this->limit;
        }
        if ($this->offset) {
            //pre($this->offset);
            $sql .= ' OFFSET ' . $this->offset;
        }

        if ($this->groupBy) {
            pre($this->groupBy);
            //pre(implode(',' , $this->groupBy));
            $sql .= ' GROUP BY ' . implode(' ' , $this->groupBy);
        }
        return $sql;
    }

    /**
     * Set the table name
     *
     * @param string $table
     * @return $this
     */
    public function table($table) {
        $this->table = $table;
        return $this;//'Method Chaining'
    }

    /**
     * Set the table name
     *
     * @param string $table
     * @return $this
     */
    public function from($table)
    {
        return $this->table($table);
    }
    /**
     * Delete Clause
     *
     * @param string $table
     * @return $this
     */
    public function delete($table = null) {
        if ($table) {
            $this->table($table);
        }
        $sql = 'DELETE FROM ' . $this->table . ' ';
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }
        $this->query($sql, $this->bindings);
        $this->reset();
        return $this;//'Method Chaining'
    }

    /**
     * Set The Data that will be stored in database table
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function data($key, $value = null) {
        if (is_array($key)) {//If $key is an array
            $this->data = array_merge($this->data, $key);
            $this->addToBindings($key);
        } else {
            $this->data[$key] = $value;
            $this->addToBindings($value);
        }
        return $this;//'Method Chaining'
    }

    /**
     * Insert Data to database
     *
     * @param string $table
     * @return $this
     */
    public function insert($table = null) {
        if ($table) {//if someone wrote a table (used the table() function)
            $this->table($table);
        }
        $sql = 'INSERT INTO ' . $this->table . ' SET ';
        $sql .= $this->setFields();
        $this->query($sql, $this->bindings);
        $this->lastId = $this->connection()->lastInsertId();
        $this->reset();
        return $this;//'Method Chaining'
    }

    /**
     * Update Data In database
     *
     * @param string $table
     * @return $this
     */
    public function update($table = null) {
        if ($table) {
            $this->table($table);
        }
        $sql = 'UPDATE ' . $this->table . ' SET ';
        $sql .= $this->setFields();
        if ($this->wheres) {
            //echo '<pre>', var_dump($this->wheres), '</pre>';
            //echo '<pre>', var_dump(implode(' ', $this->wheres)), '</pre>';
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
            //echo $sql . '<br>';
        }
        //echo $sql . '<br>';
        //pre($this->bindings);
        $this->query($sql, $this->bindings);
        $this->reset();
        return $this;//'Method Chaining'
    }

    /**
     * Set the fields for insert and update
     *
     * @return string
     */
    private function setFields() {
        $sql = '';
        foreach (array_keys($this->data) as $key) {
            $sql .= '`' . $key . '` = ? , ';
        }
        $sql = rtrim($sql, ', ');//remove the last comma
        //echo $sql . '<br>';
        return $sql;
    }

    /**
     * Add New Where clause
     *
     * @return $this
     */
    public function where() {
        $bindings = func_get_args();
        $sql = array_shift($bindings);
        $this->addToBindings($bindings);
        $this->wheres[] = $sql;
        //pre($this->wheres);
        //echo $sql . '<br>';
        return $this;//'Method Chaining'
    }

    /**
     * Add New Having clause
     *
     * @return $this
     */
    public function having()
    {
        $bindings = func_get_args();

        $sql = array_shift($bindings);

        $this->addToBindings($bindings);

        $this->havings[] = $sql;

        return $this;//'Method Chaining'
    }

    /**
     * Group By Clause
     *
     * @param array $arguments => PHP 5.6
     * @return $this
     */
    public function groupBy(...$arguments)
    {
        $this->groupBy = $arguments;

        return $this;//'Method Chaining'
    }

    /**
     * Execute the given sql statement
     *
     * @return \PDOStatement
     */
    public function query() {
        /* //Explanation of the func_get_args() function:
        //Please check: https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
        //The Splat Operator (...)
        function query() {//You could write it using the Splat Operator and remove the next line which contains the func_get_args() function like this:  function query(...$bindings) {
            //If you would use the Splat Operator (...), remove the func_get_args() function
            $bindings = func_get_args();
            //echo '<pre>', var_dump($bindings), '</pre>';
            $sql = array_shift($bindings);//Remove the 1st element of the array and store it in a variable
            echo $sql . '<br>';
            echo '<pre>', var_dump($bindings), '</pre>';
            echo count($bindings) . '<br>';
            if (count($bindings) == 1 AND is_array($bindings[0])) {
                $bindings = $bindings[0];
            }
            echo '<pre>', var_dump($bindings), '</pre>';
        }

        //Both code lines are right:
        //query('SELECT * FROM users WHERE id > ? AND id < ?', 1, 300);
        query('SELECT * FROM users WHERE id > ? AND id < ?', [1, 300]);
        */
        $bindings = func_get_args();
        $sql = array_shift($bindings);
        if (count($bindings) == 1 AND is_array($bindings[0])) {
            $bindings = $bindings[0];
        }
        try {
            $query = $this->connection()->prepare($sql);//Or   $query = static::$connection->query($sql);
            //pre($query);
            foreach ($bindings AS $key => $value) {
                //pre($key + 1); pre($value);
                $query->bindValue($key + 1, _e($value));//_e() function is in helpers.php //1 is the 1st question mark placeholder '?' and 2 is the 2nd one in $query
            }
            $query->execute();
            //pre($query);
            return $query;
        } catch (PDOException $e) {
            echo $sql . ' From Catch Exception in Database.php';
            pre($this->bindings);
            die($e->getMessage());
        }
    }

    /**
     * Get the last insert id
     *
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }

    /**
     * Add the given value to bindings
     *
     * @param mixed $value
     * @return void
     */
    private function addToBindings($value) {
        // 0 => 1
        // 1 => 3
        // 2 => 2
        // 3 => 4
        if (is_array($value)) {
            $this->bindings = array_merge($this->bindings, array_values($value));
        } else {
            $this->bindings[] = $value;
        }
    }

    /**
     * Reset All Data
     *
     * @return void
     */
    private function reset() {//Refer to the last portion of the Video no. 15 to understand why we made this function
        $this->limit = null;
        $this->table = null;
        $this->offset = null;
        $this->data = [];
        $this->joins = [];
        $this->wheres = [];
        $this->orerBy = [];
        $this->havings = [];
        $this->groupBy = [];
        $this->selects = [];
        $this->bindings = [];
    }
}