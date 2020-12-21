<?php

    class DatabaseAccess{

        private const HOST = 'localhost';

        private const USER = 'root';
        private const PASSWORD = '';

        private const DB_NAME = 'WebBirdDB';

        static public function executeQuery($query){

            try {
                $connection = self::openConnection();
                $return = array();

                $result = mysqli_query($connection, $query);

                while($elem = mysqli_fetch_assoc($result)) $return[] = $elem;

                self::closeConnection($connection);
                return $return;

            } catch (Exception $e) { return null; }
        }

        static public function executeSingleQuery($query){

            try{
                $connection = self::openConnection();

                $result = mysqli_query($connection, $query);
                $result = mysqli_fetch_assoc($result);

                self::closeConnection($connection);
                return $result;

            } catch (Exception $e) { return null; }

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


    }