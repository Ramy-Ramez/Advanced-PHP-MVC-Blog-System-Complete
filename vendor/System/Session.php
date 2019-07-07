<?php
namespace System;
class Session {
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;
    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app) {//Types Casting  //This is coming from createNewCoreObject() function in Application.php
        $this->app = $app;
    }

    public function set($key, $value) {
        echo $key . ' => ' . $value . '<br>';
    }
}