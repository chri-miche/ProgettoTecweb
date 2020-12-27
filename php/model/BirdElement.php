<?php

    require_once __ROOT__.'\model\Element.php';
    class BirdElement extends Element {

        protected function loadData() {

            $birdData = array();
            if($this->ID === null) // TODO: Make clearer exception handling.
                throw new Exception('Cannot fetch data of element that
                                    is not defined yet. First define the element.');

            $query = "
            SELECT  R.*, O.nomeScientifico AS nomeOrdine FROM ordine AS O INNER JOIN(
                SELECT Q.*, F.nomeScientifico AS nomeSpecie, F.OrdID FROM famiglia AS F INNER JOIN (
                    SELECT S.*, G.nomeScientifico AS nomeGenere, G.famID FROM genere AS G INNER JOIN
                        (SELECT * FROM specie as S WHERE tagID = '. $this->ID .' LIMIT 1)
                    AS S ON G.tagID = S.genID LIMIT 1)
                AS Q ON Q.famID = F.TagID LIMIT 1)
            AS R ON R.OrdID = O.TagID LIMIT 1;";

            $res = self::getSingleRecord($query);
            // Manca conservazione e luoghi geografici.
            // TODO: Aggiungere a conservazione una breve descrizione.
            $query = "SELECT probEstinzione, nome FROM conservazione AS C WHERE C.codice = ". $res['conservazioneID'] ." LIMIT 1;";
            $res = array_merge($res, self::getSingleRecord($query));
            // TODO: Finish, ora faccio breakfast bro.


        }


        /*** @inheritDoc */
        static public function checkID($id) {

            try{

                $query = "SELECT S.tagID FROM specie AS S WHERE S.tagID =" . $id ." LIMIT 1;";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return null; }

        }
    }