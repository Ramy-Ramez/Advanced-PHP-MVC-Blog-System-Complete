<?php
namespace App\Controllers\Admin;
use System\Controller;
class LoginController extends Controller {
    /**
     * Display Login Form
     *
     * @return mixed   //It can return a view class or a string
     */
    public function index() {
        //Creating a Default Administrator (Adding a new user)
        /*$this->db->data([
            'email'    => 'hassanzohdy@gmail.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'created'  => time(),
            'status'   => 'enabled'
        ])->insert('users');*///Insert in users table
        //Another way to write the last code:
        //$this->db->data(['email' => 'hassanzohdy@gmail.com', 'password' => password_hash('123456', PASSWORD_DEFAULT), 'created' => time(), 'status' => 'enabled'])->table('users')->insert();
        //$this->db->data(['email' => 'hassanzohdy@gmail.com', 'password' => password_hash('123456', PASSWORD_DEFAULT), 'status' => 'enabled', 'created' => time()])->insert('users');
        //pre($this->db->where('id = ?', 1)->fetch('users'));//Or:  pre($this->db->where('id = ?', 1)->table('users')->fetch());
        //echo sha1(mt_rand(1, 10000) . time());
        //die;
        //pre($_COOKIE);
        $loginModel = $this->load->model('login');
        if ($loginModel->isLogged()) {//If the user is already logged in, redirect them to the Admin Dashboard Page
            //pre($_COOKIE);
            return $this->url->redirectTo('/admin');//If the user is already logged in, redirect them to the Admin Dashboard Page
        }
        $data['errors'] = $this->errors;
        return $this->view->render('admin/users/login', $data);//$this->view calls the ViewFactory.php Class which ,in turn, calls View.php object, in turn, requests the login.php page
    }

    /**
     * Submit Login Form
     *
     * @return mixed
     */
    public function submit() {//When submit button in login form is clicked
        if ($this->isValid()) {
            $loginModel = $this->load->model('Login');
            //echo '<pre>', var_dump($loginModel), '</pre>';
            $loggedInUser = $loginModel->user();
            //pre($loggedInUser);
            //pre($_COOKIE);
            //echo 'Valid data<br>';
            //pre($_POST);
            if ($this->request->post('remember')) {//If the Remember Me checkbox is checked
                //Save login data in a Cookie (Note: We save login data in a cookie and not in session because cookie stays longer than session, as session data are removed upon closing the browser)
                $this->cookie->set('login', $loggedInUser->code);//code column in the database
            } else {
                //Save login data in session
                $this->session->set('login', $loggedInUser->code);//code column in the database
                //pre($loginModel->user());
            }
            //return $this->url->redirectTo('/admin');
            $json = [];
            $json['success'] = 'Welcome Back <h1>' . $loggedInUser->first_name . '</h1>';//Welcome message
            $json['redirect'] = $this->url->link('/admin');
            return $this->json($json);//Convert the $json array to JSON (The response from server to the AJAX request)
        } else {
            //pre($this->errors);
            //return $this->index();

            $json = [];
            //$this->errors[] = 'Another error';
            //$this->errors[] = 'More errors';
            //$json['errors'] = $this->errors;
            $json['errors'] = implode('<br>', $this->errors);
            //pre($json['errors']);
            return $this->json($json);//Convert the $json array to JSON (The response from server to the AJAX request)
        }
    }

    /**
     * Validate Login Form
     *
     * @return bool
     */
    private function isValid() {
        //Simple Server-side Validation:
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        if (! $email) {
            $this->errors[] = 'Please Insert Email address';
        } elseif (!filter_var($email , FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Please Insert Valid Email';
        }
        if (!$password) {
            $this->errors[] = 'Please Insert Password';
        }
        //Bringing the user which has the entered password
        if (!$this->errors) {//If the errors array is empty (Everything is OK as Regular Expression ONLY and not a condition that data match data in the database)
            $loginModel = $this->load->model('Login');
            //pre($loginModel);
            if (!$loginModel->isValidLogin($email, $password)) {//If the entered data is wrong (don't match data in the database) //isValidLogin() function is in LoginModel.php
                //echo 'Error<br>';
                $this->errors[] = 'Invalid Login Data';
            }
        }
        //echo empty($this->errors);
        return empty($this->errors);//Or if (count($this->errors) > 0) {return false;} else {return true;} //Or if (empty($this->errors)) {return true;} else {return false;} //Or return empty($this->errors) ? true : false;
    }
}