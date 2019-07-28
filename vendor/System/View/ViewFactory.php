<?php
namespace System\View;
use System\Application;
class ViewFactory {
    //View Factory Class is responsible to generate view objects which are basically will handle html files for view
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
    public function __construct(Application $app) {
        $this->app = $app;
    }
    /**
     * Render the given view path with the passed variables and generate new View Object for it
     *
     * @param string $viewPath
     * @param array $data
     * @return \System\View\ViewInterface
     */
    public function render($viewPath, array $data = []) {//Type Casting
        return new View($this->app->file, $viewPath, $data);//Create an object of the the View.php Class and pass those arguments to the View.php Class constructor function.
    }
}