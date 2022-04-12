<?php



namespace WebLinks\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use WebLinks\Domain\User;
use WebLinks\DAO\LinkDAO;

class UserDAO extends DAO implements UserProviderInterface {
    
    /*
     * LinkDAO used to access to Link properties
     * 
     * @var WebLink\DAO\LinkDAO
     */
    
    private $linkDAO;
    
    /**
     * setter used to attach a LinkDAO to UserDAO 
     *
     * @param WebLinks\DAO\LinkDAODAO $dao the LinkDAO used to join a link and the user that posted it
     */
    
    public function setLinkDAO( LinkDAO $dao ){
        
        $this->linkDAO=$dao;
    }

    

    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id The user id.
     *
     * @return \WebLinks\Domain\User|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql = "select * from t_user where user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row){
            return $this->buildDomainObject($row);
        }else{
            throw new \Exception("No user matching id " . $id);
            
        }    
    }
    
    
    
   
    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \MicroCMS\Domain\User
     */
    protected function buildDomainObject(array $row) {
        $user = new User();
        $user->setId($row['user_id']);
        $user->setUsername($row['user_name']);
        $user->setPassword($row['user_password']);
        $user->setRole($row['user_role']);
        $user->setSalt($row['user_salt']);
        
        return $user;
    }
    
   
     /**
     * {@inheritDoc}
     */ 
    public function loadUserByUsername($username) {
        
        $sql = "select * from t_user where user_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));
        if ($row){

            return $this->buildDomainObject($row);

        }else{

            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }    
    }

     /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {

            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));

        }

        return $this->loadUserByUsername($user->getUsername());
    }

     /**
     * {@inheritDoc}
     */
    public function supportsClass($class) {
        return 'WebLinks\Domain\User' === $class;
    }

    
     /**

     * Returns a list of all users

     *

     * @return array A list of all users

     */

    public function findAll() {

        $sql = "select * from t_user";

        $result = $this->getDb()->fetchAll($sql);


        // Convert query result to an array of domain objects

        $entities = array();

        foreach ($result as $row) {

            $id = $row['user_id'];

            $entities[$id] = $this->buildDomainObject($row);

        }

        return $entities;

    }
    
    /**
     * Removes a user from the database.
     *
     * @param integer $id The user id.
     */
    public function delete($id) {
        
        //Delete the links posted by the user
        $this->linkDAO->deleteAllByUserId($id);
        // Delete the user
        $this->getDb()->delete('t_user', array('user_id' => $id));
        
    }
    
    
    /**
     * Saves a user into the database.
     *
     * @param User $user The user to save
     */
    public function save(User $user) {
        $userData = array(
            
            'user_name'=>$user->getUsername(),
            'user_password'=>$user->getPassword(),
            'user_salt'=>$user->getSalt(),    
            'user_role'=>$user->getRole(),
            
            );
        
        
        if ($user->getId()) {
            // The user has already been saved : update it
            $this->getDb()->update('t_user', $userData, array('user_id' => $user->getId()));
        }else{    
            $this->getDb()->insert('t_user', $userData);
            // Get the id of the newly created link and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }    
}
