<?php

    require_once __ROOT__.'\model\Element.php';
    class BirdElement extends Element {

        protected function loadData() {

            $birdData = array();
            if($this->ID === null) // TODO: Make clearer exception handling.
                throw new Exception('Cannot fetch data of element that
                                    is not defined yet. First define the element.');
            // TODO: Aggiungere anche nome non scientifico.
            $query = "    
            SELECT  R.*, O.nomeScientifico AS nomeOrdine FROM ordine AS O INNER JOIN(
                SELECT Q.*, F.nomeScientifico AS nomeFamiglia, F.ordID FROM famiglia AS F INNER JOIN (
                    SELECT S.*, G.nomeScientifico AS nomeGenere, G.famID FROM genere AS G INNER JOIN
                        (SELECT * FROM specie as S WHERE tagID = ". $this->ID ." LIMIT 1)
                    AS S ON G.tagID = S.genID LIMIT 1)
                AS Q ON Q.famID = F.TagID LIMIT 1)
            AS R ON R.OrdID = O.TagID LIMIT 1;";

            $res = self::getSingleRecord($query);

            // Manca conservazione e luoghi geografici.
            /** Conservazione. */ // TODO: Add to conservazione una breve descrizione.
            $query = "SELECT probEstinzione, nome AS nomeConservazione, descrizione AS descrizioneConservazione FROM conservazione AS C WHERE C.codice = '". $res['conservazioneID'] ."' LIMIT 1;";
            $res = array_merge($res, self::getSingleRecord($query));

            /** Luoghi geografici. */
            $query = "  
                SELECT T.periodoFine, T.periodoInizio, G.nome, G.continente, G.tagID 
                FROM zonageografica AS G INNER JOIN
                    (SELECT R.zonaID, R.periodoInizio, R.periodoFine 
                    FROM residenza AS R WHERE R.specieID =". $this->ID ." )
                AS T ON T.zonaID = G.tagID;";

            /** Array inside array delle posizioni geografiche.*/
            $res['Posizioni'] = self::getMultipleRecords($query);

            return $res;

        }


        /*** @inheritDoc */
        static public function checkID($id) {

            try{

                $query = "SELECT S.tagID FROM specie AS S WHERE S.tagID =" . $id ." LIMIT 1;";
                return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return null; }
        }

        static public function getOrdini(){

            $query = "SELECT O.TagID as id, O.nomeScientifico as nome FROM ordine AS O;";
            $data = Element::getMultipleRecords($query);

            $constructedOrdini = [];
            foreach ($data as $dat) $constructedOrdini [$dat['id']] = $dat['nome'];
            return $constructedOrdini;
        }

        static  public function getFamigle(int $ordini = null){

            $query = "SELECT F.TagID as  id, F.nomeScientifico  as nome  FROM famiglia as F";
            $query .= isset($ordini) ? " WHERE $ordini = F.OrdID;" : ";";

            $data = Element::getMultipleRecords($query);

            $constructedFamilies = [];
            foreach ($data as $dat) $constructedFamilies[$dat['id']] = $dat['nome'];
            return $constructedFamilies;
        }

        static public function getGeneri(int $ordine = null, int $famiglia = null){

            /** Se ho filtro su una famiglia allora guardo quello altrimenti
                guardo l'ordine selezionato se neanche quello prendo tutto.*/
            /* Trova tutti i generi.*/
            $query = "SELECT G.tagID as id, G.nomeScientifico as nome FROM genere as G ";

            /* Se è stata selezionata la famiglia.*/
            if(isset($famiglia)) $query .= " WHERE $famiglia = G.famID";
            else if(isset($ordine)) $query .= " INNER JOIN famiglia AS f on G.famID = f.TagID WHERE F.ordID = $ordine";

            $data = Element::getMultipleRecords($query.";");

            $constructedGeneri = [];
            foreach ($data as $dat) $constructedGeneri[$dat['id']] = $dat['nome'];
            return $constructedGeneri;

        }

        static public function getBirds(int $ordine = null, int $famiglia = null, int $genere = null){

            $query = "SELECT S.tagID as id, S.nomeScientifico as nome, S.percorsoImmagine as image FROM specie AS S ";

            if(isset($genere)) $query .= " WHERE S.genID = $genere ;";
            else if(isset($famiglia))
                $query .= "INNER JOIN genere G on S.genID = G.tagID 
                INNER JOIN famiglia AS F ON F.tagID = G.famID 
                WHERE G.famID = $famiglia;";
            else if (isset($ordine))
                $query .= "INNER JOIN genere G on S.genID = G.tagID 
                INNER JOIN famiglia AS F ON F.tagID = G.famID 
                INNER JOIN ordine o on F.OrdID = o.TagID 
                WHERE O.TagID = $ordine";

            return Element::getMultipleRecords($query);

        }

        static public function getFamiglia(int $id = null){

            $query = "SELECT F.TagID as id, F.nomeScientifico as nome FROM famiglia as F WHERE F.TagID = $id LIMIT 1;";
            $app = self::getSingleRecord($query);

            $returnVal[$app['id']]= $app['nome'];
            return $returnVal;
        }

        static public function getOrdine(int $id = null){

            if(!isset($id)) return null;

            $query = "SELECT O.TagID as id, O.nomeScientifico as nome FROM ordine AS o WHERE O.TagID =$id LIMIT 1;";
            $app = self::getSingleRecord($query);

            $returnVal[$app['id']]= $app['nome'];
            return $returnVal;
        }
    }