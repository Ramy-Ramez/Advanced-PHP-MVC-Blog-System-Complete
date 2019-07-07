<?php
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