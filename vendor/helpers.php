<?php
use System\Application;
if (!function_exists('pre')) {
    /**
     * Visualize the given variable in browser
     *
     * @param mixed $var
     * @return void
     */
    function pre($var) {
        echo '<pre>';
        print_r($var);
        //Or
        //var_dump($var);
        echo '</pre>';
    }
}

if (!function_exists('pred')) {//pre die
    /**
     * Visualize the given variable in browser and exit the application
     *
     * @param mixed $var
     * @return void
     */
    function pred($var) {
        pre($var);
        die;
    }
}

if (!function_exists('array_get')) {
    /**
     * Get the value from the given array for the given key if found
     * otherwise get the default value
     *
     * @param array $array
     * @param string|int $key //string Or int
     * @param mixed $default
     * @return  mixed //This is my own line and not Hasan Zohdy's one
     */
    function array_get($array, $key, $default = null) {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('_e')) {// e stands for escaping (value filtration)
    /**
     * Escape the given value
     *
     * @param string $value
     * @return string
     */
    function _e($value) {
        return htmlspecialchars($value);//Prevents any HTML code to run
    }
}

if (!function_exists('assets')) {
    /**
     * Generate full path for the given path in public directory (to require files like: CSS files, JS files, etc...)
     *
     * @param string $path
     * @return string
     */
    function assets($path) {
        //There are TWO ways to define $app:
        //global $app; //We can use this assets() function in anywhere where $app is already defined (like in HomeController.php)
        //2nd way:
        $app = Application::getInstance();
        return $app->url->link('public/' . $path);
    }
}

if (!function_exists('url')) {
    /**
     * Generate full path for the given path
     *
     * @param string $path
     * @return string
     */
    function url($path) {
        //There are TWO ways to define $app:
        //global $app; //We can use this assets() function in anywhere where $app is already defined (like in HomeController.php)
        //2nd way:
        $app = Application::getInstance();
        return $app->url->link($path);
    }
}