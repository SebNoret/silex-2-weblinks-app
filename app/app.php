<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;


/////////////// Register global error and exception handlers////////////////////


ErrorHandler::register();
ExceptionHandler::register();

/////////////// Register error handler//////////////////////////////////////////


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    
    if($app['debug']== true){
        return;
    }else{
        switch ($code) {
            case 403:
                $message = 'Access denied.';
                break;
            case 404:
                $message = 'The requested resource could not be found.';
                break;
            default:
                $message = "Something went wrong.";
            }
            return $app['twig']->render('error.html.twig', array('message' => $message));
    }    
});


///////////////////// Register service providers////////////////////////////////


//Doctrine Dbal
$app->register(new Silex\Provider\DoctrineServiceProvider());


//twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\AssetServiceProvider(), array()); 

$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});

//session and firewall


$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new WebLinks\DAO\UserDAO($app['db']);
            },
        ),                                         
    )  
));
$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER'),
);
$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
    array('^/link/submit','ROLE_USER'),
    
);


//form

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

//monolog monitoring

$app->register(new Silex\Provider\MonologServiceProvider(), array(

    'monolog.logfile' => __DIR__.'/../var/logs/weblinks.log',
    'monolog.name' => 'Weblinks',
    'monolog.level' => $app['monolog.level']

));




//////////////////////// Register services//////////////////////////////////////




$app['dao.link'] = function ($app) {
    $linkDAO = new WebLinks\DAO\LinkDAO($app['db']);
    return $linkDAO;
};


$app['dao.user']= function($app){
    $userDAO= new WebLinks\DAO\UserDAO($app['db']);
    return $userDAO;
};


