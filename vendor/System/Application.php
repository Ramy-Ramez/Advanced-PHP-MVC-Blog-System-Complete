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
     * Application Object
     *
     * @var \System\Application
     */
    private static $instance;
    /**
     *Constructor
     *
     * @param \System\File $file
     */
    //https://stackoverflow.com/questions/12553142/when-we-should-make-the-constructor-private-why-php
    private function __construct(File $file) {//Type casting
        //What's the purpose of using a private constructor?
        //It ensures that there can be only one instance of a Class and provides a global access point to that instance and this is common with The Singleton Pattern.
        //There are several scenarios in which you might want to make your constructor private. The common reason is that in some cases, you don't want outside code to call your constructor directly, but force it to use another method to get an instance of your class.
        //Here the constructor function will be called through getInstance() function which in turn is called from index.php
        $this->share('file', $file);
        //echo '<pre>', var_dump($file), '</pre>from Application.php<br><br>'; echo '<pre>', print_r($file), '</pre>from Application.php<br><br>';
        //echo '<br>Welcome<br>';
        $this->registerClasses();
        //static::$instance = $this;
        $this->loadHelpers();
        //pre($this->file);//Testing our pre() function in helpers.php
    }
    /**
     * Get Application Instance
     *
     * @param \System\File $file
     * @return \System\Application
     */
    public static function getInstance($file = null) {//this function is called from index.php and will call the __construct() function
        if (is_null(static::$instance)) {
            static::$instance = new static($file); //'new static' is the same as 'new self'
            //This line will call the __construct() function
        }
        return static::$instance;
    }
    /**
     * Run The Application
     *
     * @return void
     */
    public function run() {
        $this->session->start();//$this->session : session property is not an Application Class property so this will call __get() method and a Session object will be returned --- the Session object will be able to call the Session Class start() method
        $this->request->prepareUrl();
        $this->file->call('App/index.php');//Requiring the index.php which is in App Folder //$this->file returns a File Object because of the code line: $this->share('file', $file);  in the constructor function
        //$this->route->getProperRoute();
        //$route = $this->route->getProperRoute();
        //pre($route);
        list($controller, $method, $arguments) = $this->route->getProperRoute();
        //$this->load->controller($controller);
        //$this->load->controller($controller);
        //Action = Controller@Method
        //$this->load->action($controller, $method, $arguments); //'load' is in the Core Classes
        $output = (string) $this->load->action($controller, $method, $arguments); //'load' is in the Core Classes //(string) will call the __toString() magic method in View.php
        //pre($output);
        //echo $output;//Echoing an object will call the __toString() magic method in View.php
        $this->response->setOutput($output);// response is in the Core Classes
        $this->response->send();//The last code to execute in our script
    }
    /**
     *Register classes in spl auto load register
     *
     * @return void
     */
    private function registerClasses() {//This function is called from inside the __construct() function
        //From PHP Manual: If your autoload function is a class method, you can call spl_autoload_register with an array specifying the class and the method to run.
        spl_autoload_register([$this, 'load']);//Any callback function must be public (This means load() function must be public)
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
            //$file = $this->file->to($class . '.php');
            $file = $class . '.php';
            //echo $file . '<br>';
        } else {//Vendor Folder
            //$file = $this->file->toVendor($class . '.php');// $this->file will call the __get() magic method because it's a non-existing property and returns a File Class object (so that we can call the toVendor() function which is a File Class function.)
            $file = 'vendor/' . $class . '.php';
            //echo $file . '<br>';
            //die($file);//to print the $file
        }
        //Making sure that the class required is really existing like a file
        if ($this->file->exists($file)) {// $this->file will call the __get() magic method because it's a non-existing property and returns a File Class object (so that we can call the exists() function which is a File Class function.)
            $this->file->call($file);//$this->file is File Class object which can call require() which is a File Class function
        }
    }
    /**
     * Load Helpers File
     *
     * @return void
     */
    private function loadHelpers() {//Loading helpers.php
        //$this->file->require($this->file->toVendor('helpers.php'));
        //require $this->file->toVendor('helpers.php');
        $this->file->call('vendor/helpers.php');
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
            'route'    => 'System\\Route',
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
        return new $object($this);//return a new object of the pre-existing class and passing '$this' (Application object) to the created object
    }
}