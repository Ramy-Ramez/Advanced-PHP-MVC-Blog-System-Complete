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
        $this->loadHelpers();
        //pre($this->file);//Testing our pre() function in helpers.php
    }
    /**
     * Run The Application
     *
     * @return void
     */
    public function run() {
        $this->session->start();//$this->session : session property is not an Application Class property so this will call __get() method and a Session object will be returned --- the Session object will be able to call the Session Class start() method
        $this->request->prepareUrl();
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
            $file = $this->file->to($class . '.php');
        } else {//Vendor Folder
            $file = $this->file->toVendor($class . '.php');// $this->file will call the __get() magic method because it's a non-existing property and returns a File Class object (so that we can call the toVendor() function which is a File Class function.)
            //die($file);//to print the $file
        }
        //Making sure that the class required is really existing like a file
        if ($this->file->exists($file)) {// $this->file will call the __get() magic method because it's a non-existing property and returns a File Class object (so that we can call the exists() function which is a File Class function.)
            $this->file->require($file);//$this->file is File Class object which can call require() which is a File Class function
        }
    }
    /**
     * Load Helpers File
     *
     * @return void
     */
    private function loadHelpers() {//Loading helpers.php
        $this->file->require($this->file->toVendor('helpers.php'));
    }
    /**
     * Get Shared Value
     *
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        //Check if the $key is stored in the container, if TRUE, retunt it, if FALSE, return NULL
        //return isset($this->container[$key]) ? $this->container[$key] : null;
        if (!$this->isSharing($key)) {
            if ($this->isCoreAlias($key)) {
                $this->share($key, $this->createNewCoreObject($key));//Create an object of the wanted class and store it in the Application $container
            } else {
                die('<strong>' . $key . '</strong> not found in application container from Application.php');
            }
        }
        //return $this->isSharing($key) ? $this->container[$key] : null;
        return $this->container[$key];
    }
    /**
     * Determine if the given key is shared through Application
     *
     * @param string $key
     * @return bool
     */
    public function isSharing($key) {//Check if the $key is stored in the container
        return isset($this->container[$key]);
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
    /**
     * Get All Core Classes with its aliases
     *
     * @return array
     */
    private function coreClasses() {
        return [
            //'Alias'  => 'Namespace'
            //Escaping is understood only by using Double Quotes and not by Single Quotes
            'request'  => 'System\\Http\\Request',
            'response' => 'System\\Http\\Response',
            'session'  => 'System\\Session',
            'cookie'   => 'System\\Cookie',
            'load'     => 'System\\Loader',
            'html'     => 'System\\Html',
            'db'       => 'System\\Database',
            'view'     => 'System\\View\\ViewFactory'
        ];
    }
    /**
     * Determine if the given key is an alias to core classes
     *
     * @param string $alias
     * @return bool
     */
    private function isCoreAlias($alias) {
        $coreClasses = $this->coreClasses();
        //pre($coreClasses);
        return isset($coreClasses[$alias]);
    }
    /**
     * Create a new object for the core class based on the given alias
     *
     * @param string $alias
     * @return object
     */
    private function createNewCoreObject($alias) {//This is called 'Lazy Loading' which means I don't create an object unless I need it! (Note: The object is created one time only and not many times).
        $coreClasses = $this->coreClasses();
        //pre($coreClasses);
        $object = $coreClasses[$alias];
        //pre($object);
        //pre($this);
        return new $object($this);//return a new object of the pre-existing class and passing $this (Application object) to the created object
    }
}