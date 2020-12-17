<?php

    require_once "DataElement.php";

    class UserElement extends DataElement{

        private $isAdmin;
        private $isModerator;


        public function getAdmin(){ return $this->isAdmin; }
        public function getModerator(){ return $this->isModerator; }


        protected function loadData(){

            try {

                if($this->getId() == null)
                    throw new Exception('Cannot fetch data of element that does not exist.');

                $this->dbAccess->openConnection();

                $query = "SELECT M.isAdmin FROM Moderatore AS M 
                        WHERE M.UserID ='" . $this->getId() . "'LIMIT 1;";

                $res = $this->dbAccess->singleRowQuery($query);
                $this->dbAccess->closeConnection();

                if(isset($res['isAdmin'])){

                    $this->isModerator = true;
                    $this->isAdmin = $res['isAdmin'];

                } else {

                    $this->isModerator = false;
                    $this->isAdmin = false;


                }

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                echo 'Errore nellottenimento dei dati.';

            }

        }


        public function checkID($id) {

            try {

                $this->dbAccess->openConnection();

                $query = "SELECT U.ID FROM Utente AS U WHERE U.ID = '$id' LIMIT 1";
                $res = $this->dbAccess->singleRowQuery($query);

                $this->dbAccess->closeConnection();

                /** Returns the count of users found. As we know to have maximum one record
                 * the user is part of the system if the count is 1 therefore it behaves like a bool. */
                return count($res);

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                return null;

            }

        }


        public function baseData() {
            // TODO: Implement retData() method.
        }

        public function fullData() {
            try {

                $this->dbAccess->openConnection();

                $findVal = $this->getId();

                $query = "SELECT * FROM Utente as U WHERE U.ID = '$findVal' LIMIT 1;";
                $res = $this->dbAccess->singleRowQuery($query);

                $this->dbAccess->closeConnection();

                return $res;

            } catch (Exception $e) {

                if($this->dbAccess->connectionIsOpen())
                    $this->dbAccess->closeConnection();

                echo $e->getMessage();

                return null;

            }
        }



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

        public function addElement($params){

            if(isset($params['email']) && isset($params['password']) && isset($params['nome'])) {
                    /** Add element to db. */
            }

        }


    }


?>