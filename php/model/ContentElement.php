<?php

    /** Elemento contenuto del database. Non lo istanziamo perchè a noi importa la natura del dato.*/
    // TODO: Make it.
    require_once __ROOT__.'\model\CommentElement.php';
    abstract class ContentElement extends Element {

        protected function loadData() {

            if($this->ID === null) return null;

            $query = "SELECT * FROM contenuto AS C WHERE ID = ". $this->ID ." LIMIT 1;";
            return array_merge(Element::getSingleRecord($query), self::getApprovazioni($this->ID));

        }

        /** * @inheritDoc  */
        static public function checkID($id) {
            try {

                $query = "SELECT C.ID FROM Contenuto AS C  WHERE C.ID = ". $id ." LIMIT 1; ";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return false; }
        }

        /** Ritorna tutte le approvazioni di un post di id = $id*/
        public static function getApprovazioni(int $id){

            try {

                $query = "SELECT COUNT(A.contentID) as voti, SUM(A.likes) as valutazioni 
                          FROM approvazione AS A  WHERE a.contentID = ". $id ." LIMIT 1; ";

                return Element::getSingleRecord($query);
            } catch (Exception $e) { return false; }

        }

        /** Ritorno array di senglaazioni oppure id di segnalazioni? Direi ID e poi si costruiscono loro le segnalazioni.*/
        public static function getSegnalazioni(int $id){

        }


    }