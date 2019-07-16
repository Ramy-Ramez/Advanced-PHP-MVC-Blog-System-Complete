<?php
//White List Routes
//This file is called from File.php
use System\Application;
$app = Application::getInstance();
//pre($app);

//Adding Routes:
$app->route->add('/', 'Main/Home');//    '/' means Home  -----    We could write 'Main/Home@index' but not important
//Link Example:    /blog/posts/my-title-post/45549
// :text is a-z 0-9 -
$app->route->add('/posts/:text/:id', 'Posts/Post');
$app->route->add('/404', 'Error/NotFound');
$app->route->notFound('/404');