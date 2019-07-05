<?php
namespace System;
class Application {
    //To store data (A container to store data)

    /**
     * Container
     *
     * @var array
     */
    private $container = [];
    /**
     *Constructor
     *
     * @param \System\File $file
     */
    public function __construct(File $file) {//Type casting
        $this->share('file', $file);
        //echo '<pre>', var_dump($file), '</pre>from Application.php<br><br>'; echo '<pre>', print_r($file), '</pre>from Application.php<br><br>';
        //echo '<br>Welcome<br>';
        $this->registerClasses();
    }
    /**
     *Register classes in spl auto load register
     *
     * @return void
     */
    private function registerClasses() {//This function is called from inside the __construct() function
        //From PHP Manual: If your autoload function is a class method, you can call spl_autoload_register with an array specifying the class and the method to run.
        spl_autoload_register([$this, 'load']);//Any callback function must be public (   load() function must be public   )
    }
    /**
     * Load Class through autoloading
     *
     * @param string $class
     * @return void
     */
    public function load($class) {//load() function must be public because it's a callback function (   in registerClasses() )
        //die($class);//To echo the name of the require-ed class ($class is instead of the message)
        //echo '<pre>', print_r($this->file), '</pre>'; //echo '<pre>', var_dump($this->file), '</pre>';
        //die;
        //The two main places of classes are: in Vendor Folder or in App Folder
        if (strpos($class, 'App') === 0) {//if the class has 'App' word in the beginning (App Folder)

        } else {//Vendor Folder
            $file = $this->file->toVendor($class . '.php');
            die($file);//to print the $file
            if ($this->file->exists($file)) {// $this->file will call the __get() magic method and returns a File Class Object (so that we can call the exists() function)
                $this->file->require($file);
            }
        }
    }
    /**
     * Get Shared Value
     *
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }
    /**
     * Get shared value dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key) {//to get an inaccessible property in the class or a non-existing one
        return $this->get($key);
    }
    /**
     * Share the given key|value Through Application
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function share($key, $value) {
        $this->container[$key] = $value;
        //echo '<pre>', var_dump($this->container), '</pre>from Application.php<br><br>'; echo '<pre>', print_r($this->container), '</pre>from Application.php<br><br>';
    }
}