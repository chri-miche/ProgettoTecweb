<?php

      /** Un report element ha una chiave a due elementi: userID e contentID.
        Come si costruisce? TODO: Controlla come fare, ti verrà in mente ora sono stanco.*/
    require_once __ROOT__.'\model\Element.php';
    class ReportElement extends Element {

        protected function loadData() {

            if($this->ID === null)
                throw new Exception('Cannot retrieve data of element not 
                defined yet. First define the element.');

            $query = "  SELECT * FROM segnalazione AS S WHERE S.contentID = " . $this->ID['contentID'] . "
                        AND S.utenteID = ". $this->ID['userID'] . " LIMIT 1";

            return $this->getSingleRecord($query);

        }

        /** * @inheritDoc  */
        public static function checkID($id) {

            try{

                $query = "SELECT S.contentID FROM segnalazione AS S  WHERE S.contentID = '$id' LIMIT 1;";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return false; }
        }


        /** Returns true if the user has reproted the given
        * post false if not. Null if error occured.
        * @param int $userID : Utente di cui verificare il report.
        * @param int $postID : Post che l'utente dovrebbe aver segnalato.
        * @return boolean  : true se l'ha reportato, false altrimenti.*/
        public function userReported(int $userID, int $postID){

            $query = "  SELECT S.contentID FROM segnalazione AS S
                        WHERE S.utenteID =" . $userID . " AND S.contentID =". $postID." LIMIT 1;";

            return (!($this->getSingleRecord($query) === null));


        }
    }