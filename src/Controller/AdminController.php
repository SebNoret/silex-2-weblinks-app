<?php
/*
 * adminController used to access to the back-office of the application
 */

namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\User;
use WebLinks\Form\Type\UserType;
use WebLinks\Form\Type\LinkType;
use WebLinks\Domain\Link;

class AdminController {
    
    /*
     * index action to back-office
     * 
     * @param Silex\Application $app Silex application
     */
    
    public function indexAction(Application $app){
        
        $links=$app['dao.link']->setAuthorDAO($app['dao.user']);
        $links=$app['dao.link']->findAll();
        $users=$app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig',array('links'=>$links,'users'=>$users));
    }
     
    /*
    * delete link action
    * 
    * 
    * @param integer $id the database id of the link to delete
    * @param Silex\Application $app Silex application
    */
    
    public function deleteLinkAction($id,Application $app){
        $app['dao.link']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The link was successfully removed.');

        // Redirect to admin home page

        return $app->redirect($app['url_generator']->generate('admin'));
        
    }
    
    /*
    *  Delete  user  action
    *     
    * @param integer $id the database id of the user to delete
    * @param Silex\Application $app Silex application
    */
    public function deleteUserAction($id ,Application $app){
        
        $app['dao.user']->setLinkDAO($app['dao.link']);
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The user was successfully removed.');

        // Redirect to admin home page

        return $app->redirect($app['url_generator']->generate('admin'));
        
    }
    
     /**
     * Add user action.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addUserAction(Request $request, Application $app) {
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $this->generatePassword($user, $app);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
        }
        return $app['twig']->render('add_user.html.twig', array(
            'title' => 'New user',
            'userForm' => $userForm->createView()));
    }
    
    /**
     * Add link action.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addLinkAction(Request $request, Application $app){
        
        $linkFormView= null;
            
            if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
                $link=new Link;
                $linkForm = $app['form.factory']->create(LinkType::class, $link);
                $linkForm->handleRequest($request);

                    if ($linkForm->isSubmitted() && $linkForm->isValid()) {
                        $user=$app['user'];
                        $link->setAuthor($user);
                        $app['dao.link']->save($link);
                        $app['session']->getFlashBag()->add('success', 'Your link was successfully added.');                                              
                    }

                $linkFormView=$linkForm->createView();        
            }         
   
    return $app['twig']->render('add_link.html.twig',array('linkForm'=>$linkFormView));
    }
    
    /**
     * editing link action.
     *
     * @param integer $id db id of the link
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */

    
    
    
    public function  editLinkAction($id, Request $request, Application $app ){
  
            $app['dao.link']->setAuthorDAO($app['dao.user']);
            $link=$app['dao.link']->find($id);
            
            
            $linkFormView= null;
            
            if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
               
                $linkForm = $app['form.factory']->create(LinkType::class, $link);
                $linkForm->handleRequest($request);
                if ($linkForm->isSubmitted() && $linkForm->isValid()) {
                        $user=$app['user'];
                        $link->setAuthor($user);
                        $app['dao.link']->save($link);
                        $app['session']->getFlashBag()->add('success', 'link informations successfully modified.');                                              
                    }

                
                $linkFormView=$linkForm->createView();
            }
            return $app['twig']->render('edit_link.html.twig',array('linkForm'=>$linkFormView));
    }
    
    /**
     * editing user action.
     *
     * @param integer $id db id of the user
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    
    
    
    public function  editUserAction($id, Request $request, Application $app ){
  
            
            $user=$app['dao.user']->find($id);
            
            
            $userFormView= null;
            
            if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
               
                $userForm = $app['form.factory']->create(userType::class, $user);
                $userForm->handleRequest($request);
                if ($userForm->isSubmitted() && $userForm->isValid()) {
                        
                    $user=$this->generatePassword($user, $app); 
                        $app['dao.user']->save($user);
                        $app['session']->getFlashBag()->add('success', 'user informations succesfully modified.');                                              
                    }

                
                $userFormView=$userForm->createView();
            }
            return $app['twig']->render('edit_user.html.twig',array('userForm'=>$userFormView));
    }
    
    /**
     * generate a password for a given user
     *
     * 
     * @param Weblinks\Domain\User $user the  given user 
     * @param Application $app Silex application
     */
    
    
    private function generatePassword(User $user, Application $app){
            
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.bcrypt'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            return $user;
    }
}
