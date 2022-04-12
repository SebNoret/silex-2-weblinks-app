<?php
// composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();


//include files for application's config
require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

$app->run();
