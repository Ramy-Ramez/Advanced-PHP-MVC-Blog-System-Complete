<?php
namespace App\Controllers\Admin\Common;
use System\Controller;
class HeaderController extends Controller {
    public function index() {
        //echo 'Hi from index() function in HeaderController.php<br>';
        $data['title'] = $this->html->getTitle();//I already have that code:   $this->html->setTitle('Categories');   in CategoriesController.php (There will be echo $title; in header.php)
        return $this->view->render('admin/common/header', $data);//$this->view returns a ViewFactory.php Object (Refer to the coreClasses() function in Application.php class)...and $view is a View.php Object (returned from render() function)
    }
}