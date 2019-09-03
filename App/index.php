<?php
use System\Application;
//White List Routes
//This file is called from File.php
//Action = Controller@Method
//Note: Any Controller without method (like: Admin/Login) means it has an 'index' method (i.e. Admin/Login@index)

$app = Application::getInstance();//Get an instance (object) of Application.php Class
//pre($app);

//Adding Routes:
//$app->route->add('/', 'Main/Home');//    '/' means Home  -----    We could write 'Main/Home@index' but not important
//$app->route->add('/', 'Home', 'POST');//    '/' means Home  -----    We could write 'Home@index' but not important
// App\Controllers\HomeController extends Controller
//Link Example:    /blog/posts/my-title-post/45549
// :text is a-z 0-9 -
//$app->route->add('/posts/:text/:id', 'Posts/Post');
//echo UPLOAD_ERR_OK;

$app->route->add('/', 'Home');//Home Page

//Admin Routes
$app->route->add('/admin/login', 'Admin/Login');
$app->route->add('/admin/login/submit', 'Admin/Login@submit', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method

//Share admin layout
$app->share('adminLayout', function ($app) {//Share admin layout in all the application to be able to use the layout anywhere (Here $value is an anonymous function so this would use the PHP built-in Closure Class.. Refer to share() function in Application.php)
    return $app->load->controller('Admin/Common/Layout');
});
//echo '<pre>', var_dump($app), '</pre>';

//Dashboard
//If you write either /admin or /admin/dashboard in the address bar, you get the same page as they are aliases to each other
$app->route->add('/admin', 'Admin/Dashboard');//Check another alias for the dashboard in the next line
$app->route->add('/admin/dashboard', 'Admin/Dashboard');//Another alias for the Dashboard
$app->route->add('/admin/submit', 'Admin/Dashboard@submit', 'POST');

//admin => users
$app->route->add('/admin/users', 'Admin/Users');
$app->route->add('/admin/users/add', 'Admin/Users@add');
$app->route->add('/admin/users/submit', 'Admin/Users@submit', 'POST');//Submitting add //method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/users/edit/:id', 'Admin/Users@edit');//Refer back to the generatePattern() function in Route.php which replaces the :id with a Regular Expression
$app->route->add('/admin/users/save/:id', 'Admin/Users@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/users/delete/:id', 'Admin/Users@delete');

//admin => users-groups (Permissions for every group)
$app->route->add('/admin/users-groups', 'Admin/UsersGroups');
$app->route->add('/admin/users-groups/add', 'Admin/UsersGroups@add');
$app->route->add('/admin/users-groups/submit', 'Admin/UsersGroups@submit', 'POST');//Submitting add //method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/users-groups/edit/:id', 'Admin/UsersGroups@edit');//Refer back to the generatePattern() function in Route.php which replaces the :id with a Regular Expression
$app->route->add('/admin/users-groups/save/:id', 'Admin/UsersGroups@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/users-groups/delete/:id', 'Admin/UsersGroups@delete');

//admin => posts
$app->route->add('/admin/posts', 'Admin/Posts');
$app->route->add('/admin/posts/add', 'Admin/Posts@add');
$app->route->add('/admin/posts/submit', 'Admin/Posts@submit', 'POST');//Submitting add  //method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/posts/edit/:id', 'Admin/Posts@edit');//Refer back to the generatePattern() function in Route.php which replaces the :id with a Regular Expression
$app->route->add('/admin/posts/save/:id', 'Admin/Posts@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/posts/delete/:id', 'Admin/Posts@delete');
//admin => Comments
$app->route->add('/admin/posts/:id/comments', 'Admin/Comments');
$app->route->add('/admin/comments/edit/:id', 'Admin/Comments@edit');
$app->route->add('/admin/comments/save/:id', 'Admin/Comments@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/comments/delete/:id', 'Admin/Comments@delete');

//admin => categories
$app->route->add('/admin/categories', 'Admin/Categories');
$app->route->add('/admin/categories/add', 'Admin/Categories@add', 'POST');
$app->route->add('/admin/categories/submit', 'Admin/Categories@submit', 'POST');//Submitting add //method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/categories/edit/:id', 'Admin/Categories@edit', 'POST');//Refer back to the generatePattern() function in Route.php which replaces the :id with a Regular Expression
$app->route->add('/admin/categories/save/:id', 'Admin/Categories@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/categories/delete/:id', 'Admin/Categories@delete', 'POST');

//Admin settings (like: Contact Us data, site condition (under maintenance))
$app->route->add('admin/settings', 'Admin/Settings');
$app->route->add('admin/settings/save', 'Admin/Settings@save', 'POST');

//Admin Contacts
$app->route->add('admin/contacts', 'Admin/Contacts');
$app->route->add('admin/contacts/reply/:id', 'Admin/Contacts@reply');//Reply to the user
$app->route->add('admin/contacts/send/:id', 'Admin/Contacts@send', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser

//admin => ads
$app->route->add('/admin/ads', 'Admin/Ads');
$app->route->add('/admin/ads/add', 'Admin/Ads@add');
$app->route->add('/admin/ads/submit', 'Admin/Ads@submit', 'POST');//Submitting add  //method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method
$app->route->add('/admin/ads/edit/:id', 'Admin/Ads@edit');//Refer back to the generatePattern() function in Route.php which replaces the :id with a Regular Expression
$app->route->add('/admin/ads/save/:id', 'Admin/Ads@save', 'POST');//method is 'POST' in order NOT for any user to be able to open that link from the address bar of browser //Submit form uses 'POST' method

//logout
$app->route->add('/logout', 'Logout');

//Not Found Routes:
//$app->route->add('/404', 'Error/NotFound');
$app->route->add('/404', 'NotFound');
$app->route->notFound('/404');