<?php


/**
 *
 * REST api controler
 *
 * 
 */

namespace WebLinks\Controller;

use Silex\Application;

class ApiController {
    
    
    
    /*
     * access to a list of all links action
     * 
     * @param Silex\Application $app Silex application
     * @return Array $responseData a Json array of all links
     */
    
    public function  getLinksAction(Application $app){
    
    $link=$app['dao.link']->setAuthorDAO($app['dao.user']);
    $links = $app['dao.link']->findAll();

    // Convert an array of objects ($links) into an array of associative arrays ($responseData)

    $responseData = array();

    foreach ($links as $link) {
        $responseData[] = array(

            'id' => $link->getId(),

            'title' => $link->getTitle(),

            'url' => $link->getUrl(),
            
            'author'=>$link->getAuthor()->getUsername()

            );

        }
        
    // Create and return a JSON response

    return $app->json($responseData);    
    }
    
    
    /*
     * access to a chosen link action
     * 
     * @param Silex\Application $app Silex application
     * @param integer $id the db id of a link
     * @return Array $responseData a Json array of the chosen link informations
     */
    
    public function getLinkAction(Application $app,$id){
        
    $link=$app['dao.link']->setAuthorDAO($app['dao.user']);
    $link = $app['dao.link']->find($id);

    // Convert an array of objects ($links) into an array of associative arrays ($responseData)

    $responseData = array();

    
        $responseData[] = array(

            'id' => $link->getId(),

            'title' => $link->getTitle(),

            'url' => $link->getUrl(),
            
            'author'=>$link->getAuthor()->getUsername()

            );

    

    // Create and return a JSON response

    return $app->json($responseData);
        
    }
    
}
