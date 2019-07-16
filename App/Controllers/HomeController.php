<?php
namespace App\Controllers;
use \System\Controller;
class HomeController extends Controller {
    public function index() {
        //echo 'Welcome from index() function in HomeController.php<br>';
        //echo $this->request->url() . '<br>';//This will call the __get() magic method in Controller.php
        $this->load->controller('Header')->index();//An example on how to call a method in any controller you want //This will call the __get() magic method in Controller.php //$this->load is an object of Loader.php class //controller('Header') returns an object of the class Header.php
    }
}