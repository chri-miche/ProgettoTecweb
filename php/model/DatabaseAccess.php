<?php


class DatabaseAccess {
    // TODO: Check all and maybe rewrite.

    /** Informazioni per l'accesso al database. */

    private const HOST = 'localhost';

    private const USER = 'root';
    private const PASSWORD = '';

    private const DB_NAME = 'WebBirdDB';

    private $connection;


    public function openConnection(){

        /** Apre la connessione con il database.*/
        $this->connection = mysqli_connect(self::HOST, self::USER,
                self::PASSWORD,self::DB_NAME);

        if (mysqli_connect_errno() != 0)
            throw new Exception(mysqli_connect_error());

    }

    /** Multiline Query. $filter to be added? */
    public function executeQuery($query){

        $result = mysqli_query($this->connection,$query);
        if(!$result) throw new Exception('Errore nella query.');

        $ret = array();

        while($elem = mysqli_fetch_assoc($result))
            $ret [] = $elem;


        return $ret;

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