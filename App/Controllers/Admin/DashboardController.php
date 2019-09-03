<?php
namespace App\Controllers\Admin;
use System\Controller;
class DashboardController extends Controller {
    public function index() {
        return $this->view->render('admin/main/dashboard');
    }

    public function submit() {
        $json['name'] = $this->request->post('fullname');
        return $this->json($json);
        //pre($_COOKIE);
        //pre($_SESSION);
         //pre($_POST);
        //pre($_FILES); return;
        //$this->validator->required('email')->email('email');//This is METHOD CHAINING (because of   using return $this;   )
        $this->validator->required('email')->email('email')->unique('email', ['users', 'email']);//This is METHOD CHAINING (because of   using return $this;   )
        //$this->validator->required('password')->minLen('password', 8);//This is METHOD CHAINING (because of   using return $this;   )
        $this->validator->required('password');
        $this->validator->match('password', 'confirm_password');//This is METHOD CHAINING (because of   using return $this;   )
        //pre($this->validator->getMessages());

        //$image = $this->request->file('image');//UploadedFile Object
        $file = $this->request->file('image');//UploadedFile Object
        //echo $file->isImage();
        if ($file->isImage()) {
            //echo $file->moveTo($this->file->to('public/images'), 'an');
            $file->moveTo($this->file->to('public/images'));
        }
    }
}