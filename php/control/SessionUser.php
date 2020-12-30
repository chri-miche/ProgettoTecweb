<?php


    // TODO: Consider if the instace has to be on page and not inside of each component
    //  at the moment every component that has one session user has an istance of the class.

    // TODO: Check if there are problems and if the user has to be updated every operation.


    // TODO: Make it static?
    require_once __ROOT__.'\model\UserElement.php';
    /* SessionUser is strongly couppled with the session so there is no way to avoid this attachment, or it it?*/
    class SessionUser {

        private $user;
        public function __construct() {$this->currentSessionUser();}

        public function currentSessionUser(){

            if(session_status() == PHP_SESSION_NONE) session_start();

            if(!isset($_SESSION['User']))
                $_SESSION['User'] = serialize(new UserElement());

            $this->user = unserialize($_SESSION['User']);

        }

        public function userIdentified(){ return !($this->user->ID === null); }

        public function getUser(){ return $this->user; }

        public function setUser(int $id){

            $this->user->loadElement($id);
            $_SESSION['User'] = serialize($this->user);

        }

        public function updateUser(){

            $this->user->loadElement($this->user->ID);
            $_SESSION['User'] = serialize($this->user);

        }

    }