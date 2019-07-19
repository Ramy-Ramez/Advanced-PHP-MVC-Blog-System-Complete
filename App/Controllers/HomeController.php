<?php
namespace App\Controllers;
use \System\Controller;
class HomeController extends Controller {
    public function index() {
        //echo 'Welcome from index() function in HomeController.php<br>';
        //echo $this->request->url() . '<br>';//This will call the __get() magic method in Controller.php
        //$this->load->controller('Header')->index();//An example on how to call a method in any controller you want //This will call the __get() magic method in Controller.php //$this->load is an object of Loader.php class //controller('Header') returns an object of the class Header.php
        //$this->view->render('home');//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object

        $this->response->setHeader('year', 1979);//Check this header in the browser inspection tools in Network, you will find year: 1979 in Response Headers
        $data['my_name'] = 'Hasan';//to be able to send data to view files (to be able to send data and print variables in HTML files), we pass $data to render() function in the next code line
        //$view = $this->view->render('home', $data);//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object
        //pre($view);
        //echo $view;//Echoing an object will call the __toString() function in View.php
        return $this->view->render('home', $data);//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object
    }
}