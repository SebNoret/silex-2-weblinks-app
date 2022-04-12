<?php
/*
 * homeController used to access to the front-office of the application
 */


namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;



class HomeController {
    
    /*
     * index action to front-office
     * 
     * @param Silex\Application $app Silex application
     */
    
    public function indexAction(\Silex\Application $app){
    
        $links=$app['dao.link']->setAuthorDAO($app['dao.user']);
        $links = $app['dao.link']->findAll();
        
    return $app['twig']->render('index.html.twig', array('links' => $links));
    }
    
    /*
     * acces action to login page of the application
     * 
     * @param Request $request Incoming request
     * @param Silex\Application $app Silex application
     */
    
    
    public function loginAction(Request $request, Application $app){
        
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        ));   
    }
    
}
