<?php
namespace App\Controllers\Admin;
use System\Controller;
class CategoriesController extends Controller {
    /**
     * Display Categories List
     *
     * @return mixed   //It can return a view class or a string
     */
    public function index() {
        //pre($this->adminLayout);
        $this->html->setTitle('Categories');
        $view = $this->view->render('admin/categories/list');//$this->view returns a ViewFactory.php Object (Refer to the coreClasses() function in Application.php class)...and $view is a View.php Object (returned from render() function)
        //pre($view);
        return $this->adminLayout->render($view);//$view is a View.php Object //$this->adminLayout returns a LayoutController.php class Object (Refer to share() function index.php in App folder)
    }

    /**
     * Open Categories Form
     *
     * @return string
     */
    public function add() {
        //return '<h1>Welcome From CategoriesController.php</h1>';
        $data['action'] = $this->url->link('/admin/categories/submit');
        return $this->view->render('admin/categories/form', $data);
    }

    /**
     * ٍSubmit for creating new category
     *
     * @return string | json
     */
    public function submit() {
        $json['success'] = 'Done';
        return $this->json($json);
    }
}