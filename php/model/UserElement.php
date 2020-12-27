<?php

    require_once __ROOT__.'\model\Element.php';
    class UserElement extends Element {

     // Nota carina. In php false viene stampato come vuoto. Sensatissimo.
     protected function loadData() {

            try {

                $userData = array();

                if($this->ID === null)
                    throw new Exception('Cannot fetch data of element that
                    is not defined yet. First define the element.');

                $query =    "SELECT * FROM Moderatore AS M  RIGHT JOIN 
                            (SELECT * FROM Utente as A WHERE A.ID =".$this->getId() . " LIMIT 1) 
                            AS U ON U.ID = M.UserID LIMIT 1;";

                $res = $this->getSingleRecord($query);

                /** Potrei semplicemente ritornare res?
                    TODO: Think about it. Prolly yes, fixing the keys in query should be the way.*/

                $userData['moderator'] = isset($res['isAdmin']);
                $userData['isAdmin'] = isset($res['isAdmin']) && $res['isAdmin'];

                $userData['nome'] = $res['nome'];
                $userData['email'] = $res['email'];
                $userData['immagine'] = $res['immagineProfilo'];

                return $userData;

            } catch (Exception $e) { echo $e; }

        }

        /** @inheritDoc  */
        public static function checkID($id) {

            try{
                    $query = "SELECT U.ID FROM Utente AS U WHERE U.ID = '$id' LIMIT 1";
                    return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return null; }

        }


        // TODO: Rename or change the return type. Why do we the id?
        //      returns the ID of the user if exists, else is false. Null if something goes wrong.
        public static function checkCredentials($email, $password){

            try {

                $query = " SELECT  U.ID FROM  Utente AS U 
                    WHERE U.email ='$email' AND U.password = '$password' LIMIT 1;";
                $res = Element::getSingleRecord($query);

                return (isset($res['ID'])) ? $res['ID'] : false;

            } catch (Exception $e) { return null; }

        }

        /** Overload the function also for ID.
         * @param string $email : Email is unique to a user so we check if it is already set.
         * @return bool : Returns true if he exists, false if not null if error occured.
         */
        private static function userExists(string $email){
            $query = "SELECT U.ID FROM utente AS U WHERE U.email='". $email. "' LIMIT 1;";
            return isset(self::getSingleRecord($query)['ID']);
        }

        /* TODO: Check this, it was done in a hurry. Not checked yet.*/
        public static function addUser(string $username, string $password, string $email){

            $query = "INSERT INTO utente(nome, email, password, immagineProfilo) VALUE 
                          ('". $username . "',  '" . $email . "', '" . $password ." ', 'defj.pg' );";
            return  (!self::userExists($email)) ? Element::addNew($query) : null;

        }

        public function getModerator(){ return $this->moderator; }
        public function getAdmin() { return $this->isAdmin; }

    }