<?php


class TagElement extends Element {

    protected function loadData() {
        // TODO: Implement loadData() method.
        try{
            if($this->ID === null)
                throw new Exception('Cannot retrieve data of element not 
                defined yet. First define the element.');

            $query = "  SELECT Q.nome, L.text as label  FROM label AS L RIGHT JOIN
                            (   SELECT T.ID, T.LabelID, T.nome 
                                FROM tag AS T WHERE T.ID = ". $this->ID ."  
                        LIMIT 1) AS Q ON L.ID = Q.LabelID LIMIT 1;";
           return $this->getSingleRecord($query);

        } catch (Exception $e) { return null; }
    }

    /** * @inheritDoc  */
    static public function checkID($id){

        try {
            $query = "SELECT T.ID  FROM Tag AS T  WHERE T.ID = ". $id. " LIMIT 1; ";
            return !(self::getSingleRecord($query) === null);

        } catch (Exception $e) { return false; }
    }


    //TODO : Unify, this is just replicated code.
    static public function ordineTags(){
        try{

            $query = "SELECT T.ID FROM tag AS T, ordine AS O WHERE O.TagID = T.ID;";
            $elem =  self::getMultipleRecords($query);

            $ret = array();
            foreach ($elem as $el)
                $ret[] = $el['ID'];

            return $ret;

        } catch (Exception $e) {return null;}
    }

    static public function famigliaTags($ordine = null){

        try {
            isset($ordine)
                /** Va fatta la join*/
                ? $query = "SELECT T.ID FROM tag AS T, Famiglia AS F, ordine AS O 
                            WHERE F.TagID = T.ID AND F.OrdID =" . $ordine . " GROUP BY T.ID;" :
                $query= "SELECT T.ID FROM tag as T, famiglia as F WHERE  T.ID = F.TagID;";


            $ret = array();

            $elem =  self::getMultipleRecords($query);

            foreach ($elem as $el){$ret[] = $el['ID'];}
            return $ret;

        } catch (Exception $e){return null;}
    }

    static public function genereTags($famiglia = null){

        try {
            isset($famiglia)
                /** Va fatta la join*/
                ? $query = "SELECT T.ID FROM tag AS T, Genere AS G, ordine AS O 
                            WHERE G.TagID = T.ID AND G.famID =" . $famiglia . " GROUP BY T.ID;" :
                $query= "SELECT T.ID FROM tag as T, genere as G WHERE  T.ID = G.TagID;";


            $ret = array();

            $elem =  self::getMultipleRecords($query);

            foreach ($elem as $el){$ret[] = $el['ID'];}
            return $ret;

        } catch (Exception $e){return null;}
    }

    // TODO: Change the input from one id to array of id's and check the combination to do the query.
    //  Example: We want all brids from a GENERE, family is not important right now. arr[ordid,famid,genid] per esempio.
    static public function specieTags($genere = null){

        try {
            isset($genere)
                /** Va fatta la join*/
                ? $query = "SELECT T.ID FROM tag AS T, Specie AS S, ordine AS O 
                            WHERE S.TagID = T.ID AND S.genID =" . $genere . " GROUP BY T.ID;" :
                $query= "SELECT T.ID FROM tag as T, specie as S WHERE  T.ID = S.TagID;";


            $ret = array();

            $elem =  self::getMultipleRecords($query);

            foreach ($elem as $el){$ret[] = $el['ID'];}
            return $ret;

        } catch (Exception $e){return null;}

    }

    public function getNome(){return $this->nome;}
    public function getLabel(){return $this->label;}

}