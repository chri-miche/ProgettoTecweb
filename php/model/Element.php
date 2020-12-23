<?php

    /** Element that has data from database or is built by it. It's our structure.*/
    require_once __ROOT__.'\model\DatabaseAccess.php';
    abstract class Element {

        private $data; /** Associative Array where all the data is stored.*/

        /** Builds a new item.
         * @param null $id : Identificatore dell'elemento cercato. */

        
        public function __construct($id = null){

            $this->data = array();
            $this->loadElement($id);

        }

        /** Loads an item if exists and sets this Element to be that.
        Checks for the id in the DB, if it exists the assignment is done.
         *  @param $id : Id dell'elemento da caricare.
         *  @return bool : True if the operation went right. */
        public function loadElement($id){

            if (!($id === null) && $this->checkID($id)) {

                    $this->data['ID'] = $id;
                    $this->data = array_merge($this->data, $this->loadData());

                    return true;
            }

            return false;

        }

        protected static function getSingleRecord(string $query) {

            try {
                return DatabaseAccess::executeSingleQuery($query);
            } catch (Exception $e) { echo $e; return null; }

         }

        protected static function getMultipleRecords(string $query){

            try {
                return DatabaseAccess::executeQuery($query);
            } catch (Exception $e) { echo $e; return null; }
        }

        abstract protected function loadData();

        /** Checks if current id is set to an element of type Data
         * in the database at the moment.
         * @param $id : Id of the element to check
         * @return true :  true if it exists, else false.
         * @return null : if there is an error in the query.
         * ____________________________________________*/
        abstract static public function checkID($id);


        public function __get($name){/* Get any value from the data structure.*/
            return (!empty($this->data))? (array_key_exists($name, $this->data) ? $this->data[$name] : null) : null;
        }


        /** Multidimensional ids are store as array in the data array. ?
            TODO: Check this out.*/
        public function getId(){ return ($this->exists())? $this->ID : null; }

        /** Insert new adata or update new data to be done.*/
        public function loadUpdate(){}

        public function exists() { return !($this->ID === null);}

        public function getData(){return $this->data;}

    }

?>