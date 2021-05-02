<?php

    class DatabaseAccess{

        private const HOST = 'localhost';

        // TODO valori di accesso al db sui server uni
        // private const USER = 'cmichele';
        // private const PASSWORD = 'keimai7Ieyipoh7W';
        // private const DB_NAME = 'cmichele';

        private const USER = 'root';
        private const PASSWORD = '';
        private const DB_NAME = 'webbirddb';

        static public function executeQuery($query){

            try {

                $connection = self::openConnection();
                $return = array();

                $result = mysqli_query($connection, $query);

                if(!($result === false)) /** Se risultato valido si può scorrere.*/
                    while($elem = mysqli_fetch_assoc($result)) $return[] = $elem;

                self::closeConnection($connection);

                return $return;

            } catch (Exception $e) { return null; }
        }

        static public function executeSingleQuery($query){

            try{

                $connection = self::openConnection();

                $result = mysqli_query($connection, $query);

                /** Se il risutlato della query è valido allora si può elaborare.*/
                if(!($result === false))
                    $result = mysqli_fetch_assoc($result);

                self::closeConnection($connection);

                return $result;

            } catch (Exception $e) { return null; }

        }

        static public function executeNoOutputUpdateQuery(string $query){

            try{

                /** Apertura della connessione.*/
                $connection = self::openConnection();
                $result = $connection->query($query);

                /** Se il risultato è vero e abbiamo modificato più di una riga.*/
                $return = $result && $connection->affected_rows > 0;
                $connection->close();

                return $return;

            } catch (Exception $e) { return false; }

        }

        static private function openConnection(){

            $connection = mysqli_connect(self::HOST, self::USER,
                self::PASSWORD, self::DB_NAME);

            if( mysqli_connect_errno() != 0)
                throw new Exception(mysqli_connect_error());

            return $connection;

        }

        static private function closeConnection($connection){

            mysqli_close($connection);
            return true;

        }

        static public function writeRecord($query){

            $connection = self::openConnection();

            mysqli_query($connection, $query);
            $ret = mysqli_insert_id($connection);

            self::closeConnection($connection);

            return $ret;

        }

        static public function deleteRecord(string $query){

            $connection = self::openConnection();
            $res = mysqli_query($connection, $query);

            self::closeConnection($connection);
            return $res;

        }

        /**
         * @param $function
         *  deve essere una funzione del tipo () -> bool
         *      che ritorni true sse bisogna fare commit,
         *      false altrimenti
         */
        static public function transaction($function) {
            $connection = self::openConnection();
            mysqli_query($connection, 'start transaction;');
            $result = $function();
            mysqli_query($connection, $result ? 'commit;' : 'rollback;');
            self::closeConnection($connection);
            return $result;
        }

    }
?>