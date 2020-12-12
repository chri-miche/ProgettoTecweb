<?php
    require_once "DBConnector.php";
    class UserData{
        /* Variables: */
        private $dbAccess;

        /* Constructor (init dbAccess) */
        public function __construct() {
            $this->dbAccess = new DBAccess();
        }

        /** Validate user. */
        public function userCredentialsCorrect($email, $password){

            $open = $this->dbAccess->openConnection();
            if(!$open) echo 'Errore di connessione con il database.';

            $query =  " SELECT  U.email, U.ID FROM  Utente AS U 
                WHERE U.email ='$email' AND U.password = '$password' LIMIT 1;";

            $res = $this->dbAccess->singleRowQuery($query);
            $this->dbAccess->closeConnection();

            return $res;

        }

        public function isAdmin($id){

            $open = $this->dbAccess->openConnection();
            if(!$open) echo 'Errore di connessione con il database.';

            $query =   "SELECT M.isAdmin FROM Moderatore AS M
                        WHERE M.UserID = '$id' LIMIT 1;";

            $res = $this->dbAccess->singleRowQuery($query);
            $this->dbAccess->closeConnection();

            return $res && $res['isAdmin'];

        }




    }



?>