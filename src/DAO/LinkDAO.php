<?php

namespace WebLinks\DAO;

use WebLinks\Domain\Link;
use WebLinks\DAO\UserDAO;

class LinkDAO extends DAO 
{
    /*
     * UserDAO used to access to user properties
     * 
     * @var WebLink\DAO\UserDAO
     */
    
    private $authorDAO;
    
    /**
     * setter used to attach a UserDAO to LinkDAO
     *
     * @param WebLinks\DAO\UserDAO $authorDAO the UserDAO used to join a link and the user that posted it
     */
       
    public function setAuthorDAO(UserDAO $authorDAO){
        $this->authorDAO=$authorDAO;
    }
    
     /**
     * Returns a list of all links, sorted by id.
     *
     * @return array A list of all WebLinks\Domain\link links.
     */
    
    public function findAll() {
        $sql = "select * from t_link order by link_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
            
            
        }
        return $entities;
    }
    
    
    /**
     * Creates an Link object based on a DB row.
     *
     * @param array $row The DB row containing Link data.
     * @return \WebLinks\Domain\Link
     */
    protected function buildDomainObject(Array $row) {
        $link = new Link();
        $link->setId($row['link_id']);
        $link->setUrl($row['link_url']);
        $link->setTitle($row['link_title']);
        $author= $this->authorDAO->find($row['user_id']);
       
        $link->setAuthor($author);
        
        return $link;
    }
    
    /**
     * Saves a link into the database.
     *
     * @param WebLinks\Domain\Link $link The link to save
     */
    public function save(Link $link) {
        $linkData = array(
            
            'user_id' => $link->getAuthor()->getId(),
            'link_title'=>$link->getTitle(),
            'link_url' => $link->getUrl()
            );
        
        
        if ($link->getId()) {
            // The link has already been saved : update it
            $this->getDb()->update('t_link', $linkData, array('link_id' => $link->getId()));
        }else{    
            $this->getDb()->insert('t_link', $linkData);
            // Get the id of the newly created link and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $link->setId($id);
        }
        
    }
    
    /**
     * Returns a link matching the supplied id.
     *
     * @param integer $id The link id.
     *
     * @return \WebLinks\Domain\Link|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql = "select * from t_link where link_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row){
            return $this->buildDomainObject($row);
        }else{
            throw new \Exception("No link matching id " . $id);
            
        }    
    }
    
    
    /**
     * Removes a link from the database.
     *
     * @param integer $id The link id.
     */
    public function delete($id) {
        // Delete the link
        $this->getDb()->delete('t_link', array('link_id' => $id));
    }
    
    /*
     * removes  links from database selected by author's id
     * 
     * @param integer $id the user id for the links
     */
    
    public function deleteAllByUserId($id){
        //delete all links
        $this->getDb()->delete('t_link', array('user_id'=>$id));
    }
  
}
