<?php
namespace App\Controllers\Admin\Common;
use System\Controller;
class SidebarController extends Controller {
    public function index() {
        //echo 'Hi from index() function in SidebarController.php<br>';
        return $this->view->render('admin/common/sidebar');//$this->view returns a ViewFactory.php Object (Refer to the coreClasses() function in Application.php class)...and $view is a View.php Object (returned from render() function)
    }
}