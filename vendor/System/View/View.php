<?php
namespace System\View;
use System\File;
class View implements ViewInterface {
    //This class is responsible for calling views "files (Requiring the views HTML files inside ِApp/Views Folder) that will contain the html code" and passing some variables for it.

    /**
     * File Object
     *
     * @var \System\File
     */
    private $file;
    /**
     * View Path
     *
     * @var string
     */
    private $viewPath;
    /**
     * Passed Data "variables" to the view path
     *
     * @var array
     */
    private $data = [];
    /**
     * The output from the view file
     *
     * @var string
     */
    private $output;
    /**
     * Constructor
     *
     * @param \System\File $file
     * @param string $viewPath
     * @param array $data
     */
    public function __construct(File $file, $viewPath, array $data) {//is called from render() function in ViewFactory.php
        $this->file = $file;
        $this->preparePath($viewPath);
        $this->data = $data;
    }
    /**
     * Prepare View Path
     *
     * @param string $viewPath
     * @return void
     */
    private function preparePath($viewPath) {
        $relativeViewPath = 'App/Views/' . $viewPath . '.php';//The Views folder
        //echo $relativeViewPath . '<br>';
        $this->viewPath = $this->file->to($relativeViewPath);
        //echo $this->viewPath . ' From View.php<br>';
        if (! $this->viewFileExists($relativeViewPath)) {
            die('<strong>' . $viewPath . ' View</strong>' . ' does not exists in Views Folder');
        }
    }
    /**
     * Determine if the view file exists
     *
     * @param string $viewPath
     * @return bool
     */
    private function viewFileExists($viewPath) {
        return $this->file->exists($viewPath);
    }
    /**
     * {@inheritDoc}
     */
    public function getOutput() {
        if (is_null($this->output)) {
            ob_start();
            //$my_name = 'Osama';
            extract($this->data);//We are putting $data in PHP Output Buffer
            //echo $this->viewPath . '<br>';
            require $this->viewPath;//We are putting all the View HTML file content in PHP Output Buffer
            //For the previous line: We can't use $this->file->call($this->viewPath) because $data is out of the scope of the call() function. For better understanding:
            /*$array = ['age' => 88, 'name' => 'Hasan'];
            extract($array);
            function test() {
                echo $age . '<br>';//This will not work because $array is out of the scope of the test() function, so you got two solutions either you use 'global' keyword or you pass $data as an a parameter to call() function
            }
            test();*/
            $this->output = ob_get_clean();//Store $this->data & $this->viewPath in PHP Output Buffer (and in $this->output) and then delete them.
        }
        return $this->output;
    }
    /**
     * {@inheritDoc}
     */
    public function __toString() {//Is called when you try to echo an object (When echoing the view in controllers)
        //return 'Welcome From View.php<br>';
        return $this->getOutput();
    }
}