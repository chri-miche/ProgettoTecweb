<?php


    require_once __ROOT__.'\model\VO\UserVO.php';
    require_once __ROOT__.'\model\DAO\UserDAO.php';

    class SessionUser {

        /** @var UserVO*/
        private $user;

        public function __construct() {

            $this->currentSessionUser();

        }

        public function currentSessionUser(){

            if(session_status() == PHP_SESSION_NONE)
                session_start();

            if(!isset($_SESSION['User']))
                $_SESSION['User'] = serialize(new UserVO());

            $this->user = unserialize($_SESSION['User']);

            return $this;

        }

        public function userIdentified(){

            return !is_null($this->user->getId());

        }

        public function getUser(){

            return $this->user;

        }

        public function setUser(int $id){

            $DAO = new UserDAO();
            $this->user = $DAO->get($id);
            $_SESSION['User'] = serialize($this->user);

        }

        public function setUserVO(UserVO $user){

            $this->user = $user;
            $_SESSION['User'] = serialize($this->user);


        }

        public function updateUser(){

            $this->setUser($this->user->getId());

        }

        public function getAdmin() {
            return $this->user->isAdmin();
        }

    }