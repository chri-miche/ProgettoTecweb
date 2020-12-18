<?php


    require_once __ROOT__.'\model\Element.php';
    class PostElement extends Element {

        /* Array con tutti i campi di post ?*/

        private $post; /** [ID, UserID, Title, Content, Data, isArchived, Likes]*/
        private $images;

        protected function loadData() {
            // TODO: Implement loadData() method.
            if($this->getId() == null)

                throw new Exception('Cannot retrive data of element not defined yet.');

                $query = " SELECT C.content, P.* FROM contenuto as C INNER JOIN
                                (SELECT P.contentID, P.title, A.Voti, A.Valutazioni  
                                FROM Post as P LEFT JOIN
                                    (SELECT A.contentID, COUNT(A.contentID) as Voti, SUM(A.likes) as Valutazioni 
                                    FROM approvazione AS A
                                    WHERE A.contentID =".$this->getId().") 
                                AS A ON A.contentID = P.contentID
                                WHERE P.contentID = ".$this->getId().")
                            AS P ON P.contentID = C.ID LIMIT 1;";

                $this->post = $this->getSingleRecord($query);


                /** Prendi tutte le immagini.*/
                $query = "SELECT I.percorsoImmagine FROM immaginipost AS I
                            WHERE I.postID = ".$this->getId().";";

                $this->images = $this->getMultipleRecords($query);

        }

        /**
         * @inheritDoc
         */
        public function checkID($id) {
            // TODO: Implement checkID() method.
            try {

                $query = "SELECT P.contentID FROM Post AS P  WHERE P.contentID = '$id' LIMIT 1; ";
                return count($this->getSingleRecord($query));


            } catch (Exception $e) {
                /* AAA */
                return false;

            }
        }

        /**
         * @inheritDoc
         */
        public function baseData() {
            // TODO: Implement baseData() method.
        }

        public function fullData() {
            // TODO: Implement fullData() method.
        }

        public function getTitle(){

            return $this->post['title'];

        }
    }