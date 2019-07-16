<?php
//phpinfo();
//echo __DIR__ . ' from index.php<br>';
//echo $_SERVER['REQUEST_URI'] . '<br>';
//echo __DIR__ . '/vendor/System/Application.php' . '<br>';//Wrong because we use Windows
//echo __DIR__ . '\vendor\System\Application.php' . '<br>';//Right
//cho __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'Application.php' . '<br>';//Right
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'Application.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'File.php';
use System\Application;
use System\File;

$file = new File(__DIR__);//Pass the project directory name
///echo $file->to('public/images/image.jpg') . ' from index.php<br>';//to test the to() function
//echo $file->toVendor('System\\Test.php') . ' from index.php<br>';//to test the toVendor() function
//die;
//$app = new Application($file);//Dependency Injection
$app = Application::getInstance($file);//Dependency Injection//The getInstance() function will call the Application Class __construct() function

//new System\Test;//to test the autoloading function in Application.php
//use App\Controllers\Users\Users;//to test the autoloading function in Application.php
//new Users;//to test the autoloading function in Application.php
//echo $app->file->to('public') . '<br>';
//echo '<pre>', var_dump($app->isCoreAlias('db')), '</pre>';
//echo '<pre>', var_dump($app->createNewCoreObject('db')), '</pre>';
//$app->session->set('name', 'Hassan');

$app->run();
//echo session_id() . '<br>';