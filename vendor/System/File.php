<?php
namespace System;
class File {
    //The class handles all files and folders

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
        //return file_exists($file);
        return file_exists($this->to($file));
    }
    /**
     * Require the given file
     *
     * @param string $file
     * @return mixed
     */
    public function call($file) {//will be used in Application.php
        //require $file;
        //echo $file . '<br>';
        return require $this->to($file); // (return require test.php) will return any values that test.php page returns (If test.php returns an array, it will be returned here)
    }
    /**
     * Generate full path to the given path in vendor folder
     *
     * @param string $path
     * @return string
     */
    public function toVendor($path) {//to Vendor Folder
        return $this->to('vendor/' . $path);
    }
    /**
     * Generate full path to the given path
     *
     * @param string $path
     * @return string
     */
    public function to($path) {//to any folder other than Vendor Folder
        return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path);//will return C:\xampp\htdocs\blog . / or \ . $path (with the right back or forward slash)
    }
}