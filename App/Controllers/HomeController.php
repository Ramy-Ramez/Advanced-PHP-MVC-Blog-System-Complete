<?php
namespace App\Controllers;
use \System\Controller;
class HomeController extends Controller {
    public function index() {
        //echo 'Welcome from index() function in HomeController.php<br>';
        //echo $this->request->url() . '<br>';//This will call the __get() magic method in Controller.php
        //$this->load->controller('Header')->index();//An example on how to call a method in any controller you want //This will call the __get() magic method in Controller.php //$this->load is an object of Loader.php class //controller('Header') returns an object of the class Header.php
        //$this->view->render('home');//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object

        //$this->response->setHeader('year', 1979);//Check this header in the browser inspection tools in Network, you will find year: 1979 in Response Headers
        //$data['my_name'] = 'Hasan';//to be able to send data to view files (to be able to send data and print variables in HTML files), we pass $data to render() function in the next code line
        //$view = $this->view->render('home', $data);//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object
        //pre($view);
        //echo $view;//Echoing an object will call the __toString() function in View.php
        //return $this->view->render('home', $data);//$this->view() will call the __get() magic method in Controller.php (class HomeController extends Controller) which in turn will call get($key) function in Application.php ('view' is stored in the Core Classes) and return a ViewFactory object


        /*$this->db->data([//'db' is in the Core Classes
            'name' => 'hasan',
            'age'  => 13
        ])->insert('users');*///Or the same is:       $this->db->data(['name' => 'hasan', 'age'  => 13])->table('users')->insert();
        //We can also use this (Method Chaining) instead of the last code:
        //$this->db->data('name', 'hasan')->data('age', 13);

        //$this->db->query('TRUNCATE TABLE users');//to execute any query you want
        //$this->db->query('Insert INTO users SET email=?, status=?', 'hassanzohdy@gmail.com', 'enabled');
        /*echo $this->db->data([//'db' is in the Core Classes
            'email' => 'hasan@gmail.com',
            'image'  => '<strong>disabled</strong>'
        ])->insert('users')->lastId();*/
        /*$user = $this->db->query('SELECT * FROM users WHERE id = ?', 4)->fetch();//Note: In connect() function in Database.php we have this code:  static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//Makes the default form of the returned values from queries as OBJECTS          static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//Makes the default form of the returned values from queries as OBJECTS
        pre($user);
        echo $user->image;*/
        //$this->db->data('email', 'shadyaskar@yahoo.com')->where('email = ?', 2)->update('users');//'Method Chaining'
        //$this->db->data('email', 'hassanzohdy@gmail.com')->where('id = ?', 1)->update('users');//'Method Chaining'
        //$this->db->data('first_name', 'hasan')->update('users');//'Method Chaining'

        //$user = $this->db->select('*')->from('users')->orderBy('id', 'DESC')->fetch();
        //pre($user);
        //$users = $this->db->select('*')->from('users')->orderBy('id', 'DESC')->fetchAll();
        //pre($users);
        //$users = $this->db->select('*')->from('users')->where('id > ? AND id < ?', 1, 4)->orderBy('id', 'DESC')->fetchAll();
        //pre($users);
        //$this->db->where('id > ?', 3)->delete('users');
        //pre($this->db->fetchAll('users'));

        /*pre($this->db->where('id != ?', 2)->fetchAll('users'));
        echo 'Number of rows from the last fetch is <strong>' . $this->db->rows() . '</strong> From HomeController.php<br>';
        pre($this->db->fetchAll('users'));
        echo 'Number of rows from the last fetch is <strong>' . $this->db->rows() . '</strong> From HomeController.php<br>';*/


        $users = $this->load->model('Users');
        //pre($users->all());
        pre($users->get(1));
    }
}