<?php
namespace System;
abstract class Model {
    /**
     * Application Object
     *
     * @var \System\Application
     */
    protected $app;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call shared application objects dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
    /**
     * Get all Model Records
     *
     * @return array
     */
    public function all()
    {
        return $this->orderBy('id', 'DESC')->fetchAll($this->table);
    }

    /**
     * Get Record By Id
     *
     * @param int $id
     * @return \stdClass | null
     */
    public function get($id) {
        return $this->where('id = ?' , $id)->fetch($this->table);
    }

    /**
     * Determine if the given value of the key exists in table
     *
     * @param mixed $value
     * @param string $key
     * @return bool
     */
    public function exists($value, $key = 'id')
    {
        return (bool) $this->select($key)->where($key .'=?' , $value)->fetch($this->table);
    }

    /**
     * Delete Record By Id
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        return $this->where('id = ?' , $id)->delete($this->table);
    }

    /**
     * Call Database methods dynamically
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args) {//Is called when we invoke inaccessible methods in object context
        //echo $method . '<br>';
        //pre($args);
        return call_user_func_array([$this->app->db, $method], $args);//Calls the $method inside the Database.php Class, $args are the parameters which are passed from the inaccessible method
    }
}