<?php
namespace System;
use http\Encoding\Stream\Inflate;

class Loader {
    //This class is responsible for loading Controllers "Classes" AND Models "Classes" located in App directory

    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;
    /**
     * Controllers Container
     *
     * @var array
     */
    private $controllers = [];
    /**
     * Models Container
     *
     * @var array
     */
    private $models = [];
    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }
    /**
     * Call the given controller with the given method and pass the given arguments to the controller method
     * @param string $controller
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function action($controller, $method, array $arguments) {//Type Casting
        //Load a class and call a method from it then pass the given arguments to it
        $object = $this->controller($controller);
        //pre($object);
        //($method);
        //pre($arguments);
        //To call a certain class method:   call_user_func_array([$class or $object, $classMethod], $parametersArray passed to the method)
        //Example 1: call_user_func_array(['App\\Controllers\\HomeController', 'index'], $arguments);//Using the Class name
        //Example 2: call_user_func_array([$this->controllers[$this->getControllerName($controller)], 'index'], $arguments);//Using the Class name
        return call_user_func_array([$object, $method], $arguments);//Using the Object
    }
    /**
     * Call the given controller
     *
     * @param string $controller
     * @return object
     */
    public function controller($controller) {//This function is called from run() function in Application.php
        //Load the given controller class and store it in controllers container
        $controller = $this->getControllerName($controller);
        //echo $controller . '<br>';
        if (!$this->hasController($controller)) {
            $this->addController($controller);
        }
        return $this->getController($controller);
    }
    /**
     * Determine if the given class|controller exists in the controllers container
     *
     * @param string $controller
     * @return bool
     */
    private function hasController($controller) {
        return array_key_exists($controller, $this->controllers);
    }
    /**
     * Create new object for the given controller and store it in controllers container
     *
     * @param string $controller
     * @return void
     */
    private function addController($controller) {
        //echo 1;
        $object = new $controller($this->app);//Create a new object of the $controller and pass the $app object to the controller object
        //Home
        //App\\Controllers\\HomeController
        $this->controllers[$controller] = $object;//Storing the controller object in the $controllers array
    }
    /**
     * Get the controller object
     *
     * @param string $controller
     * @return object
     */
    private function getController($controller) {
        return $this->controllers[$controller];
    }
    /**
     * Get the full class name for the given controller
     *
     * @param string $controller
     * @return string
     */
    private function getControllerName($controller) {//Receives the controller name (Ex: Home) and turns it into the full namespace name 'App\\Controllers\\Home'
        $controller .= 'Controller'; //Example: HomeController
        $controller = 'App\\Controllers\\' . $controller;
        return str_replace('/', '\\', $controller);
    }
    /**
     * Call the given model
     *
     * @param string $model
     * @return object
     */
    public function model($model) {

    }
    /**
     *Determine if the given class exists in the models container
     *
     * @param string $model
     * @return bool
     */
    private function hasModel($model) {

    }
    /**
     * Create new object for the given model and store it in models container
     *
     * @param string $model
     * @return void
     */
    private function addModel($model) {

    }
    /**
     * Get the model object
     *
     * @param string $model
     * @return object
     */
    private function getModel($model) {

    }
}