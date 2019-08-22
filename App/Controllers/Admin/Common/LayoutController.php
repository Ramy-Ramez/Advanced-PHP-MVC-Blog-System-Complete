<?php
namespace App\Controllers\Admin\Common;
use System\Controller;
use System\View\ViewInterface;
class LayoutController extends Controller {
    /**
     * Render the layout with the given view Object
     *
     * @param \System\View\ViewInterface $view
     * @return
     */
    public function render(ViewInterface $view) {//Type Hinting ($view is an object of the View.php Class which implements the ViewInterface.php Class)
        //echo 'Hi from index() function in LayoutController.php<br>';
        //pre($view);
        $data['content'] = $view;
        //echo $view;

        //In Layout, we need to call the header, footer and sidebar:
        $data['header']  = $this->load->controller('Admin/Common/Header')->index();//Header
        $data['sidebar'] = $this->load->controller('Admin/Common/Sidebar')->index();//Sidebar
        $data['footer']  = $this->load->controller('Admin/Common/Footer')->index();//Footer

        return $this->view->render('admin/common/layout', $data);//$this->view returns a ViewFactory.php object
    }
}