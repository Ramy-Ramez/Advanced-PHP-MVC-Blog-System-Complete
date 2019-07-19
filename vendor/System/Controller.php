<?php
namespace System;
abstract class Controller {
    //abstract because it will be extends-ed by all controllers (will not be able to create objects of it)

    /**
     * Application Object
     *
     * @var \System\Application
     */
    protected $app;
    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }
    /**
     * Call shared application objects dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->app->get($key);
    }
}