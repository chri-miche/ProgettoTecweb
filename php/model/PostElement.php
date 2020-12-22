<?php

    require_once __ROOT__.'\model\Element.php';
    /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
    class PostElement extends Element {

        /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
        protected function loadData() {
            if($this->ID === null)
                throw new Exception('Cannot retrieve data of element not 
                defined yet. First define the element.');

            $query = "  SELECT C.content, C.isArchived, C.UserID AS userID, P.title, P.Voti as voti, P.Valutazioni as valutazioni 
                        FROM contenuto as C INNER JOIN
                            (SELECT P.contentID, P.title, A.Voti, A.Valutazioni  
                            FROM Post as P LEFT JOIN
                                (SELECT A.contentID, COUNT(A.contentID) as Voti, SUM(A.likes) as Valutazioni 
                                FROM approvazione AS A
                                WHERE A.contentID =".$this->getId().") 
                            AS A ON A.contentID = P.contentID
                            WHERE P.contentID = ".$this->getId().")
                        AS P ON P.contentID = C.ID LIMIT 1;";

            $post = $this->getSingleRecord($query); /*Can't be null, we know there is at least one record.*/

            /** Prendi tutte le immagini.*/
            $query = "SELECT I.percorsoImmagine FROM immaginipost AS I
                      WHERE I.postID = ".$this->getId().";";

            $images = array();

            foreach ($this->getMultipleRecords($query) as $image)
               $images [] = $image['percorsoImmagine'];

            $imagesList['immagini'] = $images;
            return array_merge($post, $imagesList);

        }

        /*** @inheritDoc  */
        public static function checkID($id) {
            // TODO: Implement checkID() method.
            try {

                $query = "SELECT P.contentID FROM Post AS P  WHERE P.contentID = '$id' LIMIT 1; ";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return false; }

        }

        // TODO: Move this to somewhhere it makes sense. Why on earth would i do
        //  a get all new post inside an element of Post?
        public static function getNewest(int $range, int $offset){

            try{
                $query = " SELECT P.contentID FROM post AS P, contenuto AS C 
                        WHERE P.contentID = C.ID ORDER BY  C.data LIMIT ". $range ." OFFSET ". $offset .";";

                $res = self::getMultipleRecords($query);

                $return = array();
                foreach ($res as $r){ $return[] = $r['contentID']; }

                return $return;

            } catch (Exception $e){return false;}

        }

        public static function getBest(int $range, int $offset){

            try{

                $query = "";

                $res = self::getMultipleRecords($query);

                $return = array();
                foreach ($res as $r){ $return[] = $r['contentID']; }

                return $return;

            } catch (Exception $e){return false;}

        }

        public function relatedTagIds(){

            $ret = array();

            $query = "SELECT C.tagID as ID FROM Citazione AS C WHERE C.postID = ". $this->ID .";";
            foreach (Element::getMultipleRecords($query) as $result)  $ret [] = $result['ID'];

            return $ret;

        }

        // TODO: Implement all fields with get function? (To be clearer).
        public function getPictures(){ return $this->immagini;}
        public function getTitle(){ return $this->title;}
        public function getCreator() { return $this->userID;}

    }