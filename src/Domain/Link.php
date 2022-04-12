<?php

namespace WebLinks\Domain;
use WebLinks\Domain\User;

class Link 
{
    /**
     * Link id.
     *
     * @var integer
     */
    private $id;

    /**
     * Link title.
     *
     * @var string
     */
    private $title;

    /**
     * Link url.
     *
     * @var string
     */
    private $url;
    
    /**
     * the author of the posted link
     *  
     * @var  WebLinks\Domain\User
     */
    
    private $author;
    
    public function getAuthor(){
        return $this->author;
    }
    
    public function setAuthor(User $user){
        $this->author=$user;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }
}
