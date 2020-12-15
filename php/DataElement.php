<?php

    require_once "DBConnector.php";

    abstract class DataElement{

        private $id;

        protected $dbAccess;

        /** Builds a new item.
         * @param null $id  */
        public function __construct($id = null){

            if($id && $this->checkID($id)) {
                $this->id = $id; $this->loadData();
            } $this->dbAccess = new DBAccess();

        }



    /** Loads an item if exists and sets this Element to be that.
        Checks for the id in the DB, if it exists the assignment is done.
     *
     *  @param $id : Id dell'elemento da caricare.
     *  @return bool : True if the operation went right. */
        public function loadElement($id){

            if($id && $this->checkID($id)) {

                $this->id = $id;
                $this->loadData();

                return true;
            }

            return false;

        }


        abstract protected function loadData();

        /** Checks if current id is set to an elment of type Data
         * in the database at the moment.S
         * @param $id : Id of the element to check
         * @return bool : true if it exists, else false.
         * ____________________________________________*/
        abstract public function checkID($id);


        /** Gets ids from all relations?*/
        abstract public function baseData();

        abstract public function fullData();

        public function getId(){ return $this->id; }
    }



?>