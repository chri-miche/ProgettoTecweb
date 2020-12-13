<?php

    require_once "DBConnector.php";


    class DatabaseElement{

        protected $dbAccess;
        public function __construct(){ $this->dbAccess = new DBAccess(); }

    }


    class User extends DatabaseElement {
        /* Variables: */
        /** Data related to a user that has to be accessible all the time.*/
        private $id;
        private $isAdmin; private $isModerator;

        /* Constructor (init dbAccess). Creates a new user if id is null
            or not valid the user has null fields. */
        public function __construct($id = null) { parent::__construct();

            /** Se l'utente non è nullo e l'utente esiste. */
            if($id && $this->checkUser($id)){

                $this->id = $id;
                $this->getMinimalData($id);

            } else { /** Altrimenti non abbiamo un utente inizializzato.*/
                $this->id = null; $this->isAdmin = null; $this->isModerator = null;
            }

        }

        /** Checks if user the given id is a valid user.
            @return: bool, true if the user exists false if it doesn't.  */
        public function checkUser($id){

            try{

                $this->dbAccess->openConnection();

                $query = "SELECT U.ID FROM Utente AS U WHERE U.ID = '$id' LIMIT 1";
                $res = $this->dbAccess->singleRowQuery($query);

                $this->dbAccess->closeConnection();

                /** Returns the count of users found. As we know to have maximum one record
                 the user is part of the system if the count is 1 therefore it behaves like a bool. */
                return count($res);

            } catch (Exception $e){

                echo $e->getMessage();
                $this->dbAccess->closeConnection();

            }
        }

        /** Loads a user as the current user.
         *  (Yet to consider if the session should change here).
         *  @return true if the user is loaded and exist
         *  @return false if the user does not exist  */
        public function loadUser($id){

            if($id && $this->checkUser($id)){

                $this->id = $id; $this->getMinimalData($id);
                return true;

            }

            return false;

        }

        private function getMinimalData($id){

            try{
                /** Open Connection with DB.*/
                $this->dbAccess->openConnection();

                /** Find in moderators the user.*/
                $query = "SELECT M.isAdmin FROM moderatore AS M WHERE M.UserID = '$id' LIMIT 1";
                $res = $this->dbAccess->singleRowQuery($query);

                $this->dbAccess->closeConnection();
                /** If the filed isadmin is set that means the query had success?*/
                if(isset($res['isAdmin'])){

                    $this->isModerator = true;
                    $this->isAdmin = $res['isAdmin'];

                } else {

                    $this->isModerator = false;
                    $this->isAdmin = false;

                }

            } catch (Exception $e) {

                echo $e->getMessage();

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

            }

        }
        /**************** Getters : ******************************/

        public function getId(){ return $this->id; }
        public function getAdmin(){ return $this->isAdmin; }
        public function getModerator(){ return $this->isModerator; }

        /****************************************************** */


        /** Validate user. */
        public function userCredentialsCorrect($email, $password){

            $this->dbAccess->openConnection();

            $query =  " SELECT  U.email, U.ID FROM  Utente AS U 
                WHERE U.email ='$email' AND U.password = '$password' LIMIT 1;";

            $res = $this->dbAccess->singleRowQuery($query);
            $this->dbAccess->closeConnection();

            return $res;

        }

    }

?>