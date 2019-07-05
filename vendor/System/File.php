<?php
namespace System;
class File {
    //Handles all files and folders

    /**
     *Directory Separator
     *
     * @const string
     */
    const DS = DIRECTORY_SEPARATOR;
    /**
     * Root Path
     *
     * @var string
     */
    private $root;
    /**
     * Constructor
     *
     * @param string $root
     */
    public function __construct($root) {
        $this->root = $root;
        //echo '<pre>', var_dump($root), '</pre>from File.php<br><br>'; echo '<pre>', print_r($root), '</pre> from File.php<br><br>';
    }
    /**
     * Determine whether the given file path exists
     *
     * @param string $file
     * @return bool
     */
    public function exists($file) {
        return file_exists($file);
    }
    /**
     * Require the given file
     *
     * @param string $file
     * @return void
     */
    public function require($file) {
        require $file;
    }
    /**
     * Generate full path to the given path in vendor folder
     *
     * @param string $path
     * @return string
     */
    public function toVendor($path) {//to Vendor Folder
        return $this->to($path);
    }
    /**
     * Generate full path to the given path
     *
     * @param string $path
     * @return string
     */
    public function to($path) {
        return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path);
    }
}