<?php

    require_once __ROOT__.'\model\Element.php';
    class UserElement extends Element {

        private $isAdmin; private $isModerator;
        // TODO: Should i move the data to the Element? Prolly yes
        private $userData;/** Data relative to the user will be stored here. */

        protected function loadData() {
            // TODO: Implement loadData() method.

            try {

                if($this->getId() == null)
                    throw new Exception('Cannot fetch data of element that
                                    is not defined yet. First define the element.');


                $this->dbAccess->openConnection();

                $query =    "SELECT * FROM Moderatore AS M  RIGHT JOIN 
                            (SELECT * FROM Utente as A WHERE A.ID =".$this->getId() . " LIMIT 1) 
                            AS U ON U.ID = M.UserID LIMIT 1;";


                $res = $this->dbAccess->singleRowQuery($query);
                $this->dbAccess->closeConnection();

                $this->isModerator = isset($res['isAdmin']);
                $this->isAdmin = isset($res['isAdmin']) && $res['isAdmin'];

                /** Build of the data. */
                $this->userData['Nome'] = $res['nome'];
                $this->userData['Email'] = $res['Email'];
                $this->userData['Immagine'] = $res['immagineprofilo'];

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                echo 'Errore nell ottenimento dei dati';

            }

        }

        /** @inheritDoc  */
        public function checkID($id) {
            // TODO: Implement checkID() method.

            try{

                $this->dbAccess->openConnection();

                $query = "SELECT U.ID FROM Utente AS U WHERE U.ID = '$id' LIMIT 1";
                $res = $this->dbAccess->singleRowQuery($query);

                $this->dbAccess->closeConnection();

                return count($res);

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                return null;

            }
        }

        /** @inheritDoc  */
        public function baseData() {
            // TODO: Implement baseData() method.
            return $this->userData;
        }

        public function fullData() {
            // TODO: Implement fullData() method.
        }

        // TODO: Rename or change the return type. Why do we the id?
        public function checkCredentials($email, $password){

            try {

                $this->dbAccess->openConnection();

                $query = " SELECT  U.ID FROM  Utente AS U 
                    WHERE U.email ='$email' AND U.password = '$password' LIMIT 1;";

                $res = $this->dbAccess->singleRowQuery($query);
                $this->dbAccess->closeConnection();

                return $res;

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                return null;

            }

        }


        public function getModerator(){ return $this->isModerator; }
        public function getAdmin() { return $this->isAdmin; }

    }