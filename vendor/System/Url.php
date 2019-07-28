<?php
namespace System;
class Url {
    //This class generates the full link for any link in your website

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
     * Generate full link for the given path
     *
     * @param string $path
     * @return string
     */
    public function link($path) {
        //echo $this->app->request->baseUrl() . trim($path, '/');
        return $this->app->request->baseUrl() . trim($path, '/');//baseUrl() function is in Request.php
    }

    /**
     * Redirect to the given path
     *
     * @param string $path
     * @return void
     */
    public function redirectTo($path) {
        header('location:' . $this->link($path));
        exit;
    }
}