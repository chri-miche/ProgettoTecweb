<?php
    /** Unca classse che faccia la connessione ele query.
        Il razionale dietro allo sviluppo di php.*/
    class DBAccess {

        /** Informazioni per l'accesso al database. */

        private const HOST = 'localhost';

        private const USER = 'root';
        private const PASSWORD = '';

        private const DB_NAME = 'WebBirdDB';

        private $connection;


        public function openConnection(){
            /** Apre la connessione con il database.*/
            $this->connection = mysqli_connect(DBAccess::HOST, DBAccess::USER,
                DBAccess::PASSWORD,DBAccess::DB_NAME);

            return !mysqli_connect_errno();
        }


        public function executeQuery($query){

            $result = mysqli_query($this->connection, $query);

            if(mysqli_num_rows($result) != 0) {

                $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_free_result($result);

                return $res;
            }

            return null;
        }

        /** Queries known to get only one record in the db.
        They fetch the data and create an associative array.*/
        public function singleRowQuery($query){

            $result = mysqli_query($this->connection, $query);

            if(mysqli_num_rows($result) != 0) {

               $res =  mysqli_fetch_assoc($result);
               mysqli_free_result($result);

               return $res;
            }

            return null;

        }


        public function closeConnection(){

            mysqli_close($this->connection);

        }

    }