<?php
namespace App\Controllers\Admin;
use System\Controller;
class DashboardController extends Controller {
    public function index() {
        return $this->view->render('admin/main/dashboard');
    }

    public function submit() {
        //pre($_COOKIE);
        //pre($_SESSION);
        //$this->validator->required('email')->email('email');//This is METHOD CHAINING (because of   using return $this;   )
        $this->validator->required('email')->email('email')->unique('email', ['users', 'email']);//This is METHOD CHAINING (because of   using return $this;   )
        $this->validator->required('password')->minLen('password', 8);//This is METHOD CHAINING (because of   using return $this;   )
        $this->validator->match('password', 'confirm_password');//This is METHOD CHAINING (because of   using return $this;   )
        pre($this->validator->getMessages());
    }
}