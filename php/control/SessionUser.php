<?php


    // TODO: Consider if the instace has to be on page and not inside of each component
    //  at the moment every component that has one session user has an istance of the class.

    require_once __ROOT__.'\model\UserElement.php';
    class SessionUser {

        private $user;

        public function __construct() {

            $this->currentSessionUser();

        }


        public function currentSessionUser(){

            session_start();

            if(isset($_SESSION['User']) && $_SESSION['User'])
                $this->user = unserialize($_SESSION['User']);

            else
                $this->user = new UserElement();


        }

        public function userIdentified(){

            if(isset($this->user) && $this->user->getId() != null){

                /** User is identified. */

            }

        }

        public function getUser(){ return $this->user; }


    }