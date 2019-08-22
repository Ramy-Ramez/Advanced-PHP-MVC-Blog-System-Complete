<?php
namespace App\Controllers\Admin\Common;
use System\Controller;
class FooterController extends Controller {
    public function index() {
        //echo 'Hi from index() function in FooterController.php<br>';
        return $this->view->render('admin/common/footer');//$this->view returns a ViewFactory.php Object (Refere to the coreClasses() function in Application.php class)...and $view is a View.php Object (returned from render() function)

    }
}