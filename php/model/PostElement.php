<?php

    require_once __ROOT__.'\model\Element.php';
    /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
    class PostElement extends Element {

        /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
        protected function loadData() {
            if($this->ID === null)
                throw new Exception('Cannot retrieve data of element not 
                defined yet. First define the element.');

            $query = "  SELECT C.content, C.UserID AS userID, P.title, P.Voti as voti, P.Valutazioni as valutazioni 
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
        // TODO : Write nicelu.
        public function checkID($id) {
            // TODO: Implement checkID() method.
            try {

                $query = "SELECT P.contentID FROM Post AS P  WHERE P.contentID = '$id' LIMIT 1; ";
                return !($this->getSingleRecord($query) === null);

            } catch (Exception $e) { return false; }

        }

        // TODO: Implement all fields with get function? (To be clearer).
        public function getPictures(){ return $this->immagini;}
        public function getTitle(){ return $this->title;}
        public function getCreator() { return $this->post;}

    }