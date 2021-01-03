<?php

    require_once __ROOT__.'\model\ContentElement.php';
    class PostElement extends ContentElement {

        /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
        protected function loadData() {

            $data = parent::loadData();

            if($this->ID === null)  throw new Exception('Cannot retrieve data of 
                                    element not defined yet. First define the element.');

            $query = "  SELECT P.contentID, P.title 
                        FROM Post as P  WHERE P.contentID = ".$this->ID." LIMIT 1;";
                     
            $data = array_merge($data, Element::getSingleRecord($query));

            /** Prendi tutte le immagini.*/ // TODO: Move to function.
            $query = "SELECT I.percorsoImmagine FROM immaginipost AS I
                      WHERE I.postID = ".$this->getId().";";

            $data['immagini'] = array();

            foreach ($this->getMultipleRecords($query) as $image)
                $data ['immagini'][] = $image['percorsoImmagine'];

            return $data;

        }

        /*** @inheritDoc  */
        public static function checkID($id) {

            try {

                $query = "SELECT P.contentID FROM Post AS P  WHERE P.contentID = ". $id ." LIMIT 1; ";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return false; }

        }

        public static function getUserPosts(int $usid, int $limit =  0,  int $offset = 0){

            try{

                $query = "  SELECT * FROM Post AS P INNER JOIN 
                                (SELECT * FROM contenuto AS C WHERE C.UserID = ". $usid ." )
                            AS C ON P.contentID = C.ID";

                $query .= $limit > 0 ? $offset > 0 ? " LIMIT ". $limit .", ". $offset . ";" : " LIMIT ". $limit .";" : ";" ;
                $result = self::getMultipleRecords($query);

                $return = [];
                foreach ($result as $res) $return[] = new PostElement(0, $res);

                return $return;

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