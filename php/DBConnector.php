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

            if (mysqli_connect_errno() != 0)
                throw new Exception(mysqli_connect_error());

        }

        /** Multiline Query. $filter to be added? */
        public function executeQuery($query){

            /***/

        }

        /** Queries known to get only one record in the db.
        They fetch the data and create an associative array.*/
        public function singleRowQuery($query){

            $result = mysqli_query($this->connection, $query);
            if(!$result) throw new Exception('Errore nella esecuzione della query.');

            $res =  mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            return $res;

        }

        public function connectionIsOpen(){ return $this->connection; }

        public function closeConnection(){

            mysqli_close($this->connection);
            $this->connection = false;

        }

    }