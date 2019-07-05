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
$app = new Application($file);//Dependency Injection

new System\Test;