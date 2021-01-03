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
            // TODO: Add label.
            $query = "SELECT T.ID  FROM Tag AS T  WHERE T.ID = ". $id. " LIMIT 1; ";
            return !(self::getSingleRecord($query) === null);

        } catch (Exception $e) { return false; }
    }


    //TODO : Redo. Add limits and offset.
    static public function ordineTags(int $limit = -1, int $offset = -1){
        try{

            $query = "  SELECT * FROM label AS L RIGHT JOIN(
                            SELECT * FROM tag AS T, ordine AS O 
                            WHERE O.TagID = T.ID) 
                        AS T ON L.ID = T.ID ";

            if($limit > -1) $query .= " LIMIT " . $limit;
            if($offset > -1) $query .= " OFFSET " . $offset;
            $query .= ";";

            $result = self::getMultipleRecords($query);

            $return = [];
            foreach ($result as $res)
                $return[] = new TagElement(null, $res);

            return $return;

        } catch (Exception $e) {return null;}
    }

    // TODO: Fix those too.
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

    public static function getInterests(int $usid, int $number){

        try{
            // Selects all the elements.
            $query = "  SELECT * FROM tag AS T INNER JOIN 
                            (SELECT I.tagID FROM interesse AS I WHERE I.userID =". $usid." LIMIT ". $number. ") 
                        AS I ON T.ID = I.tagID";

            $result = self::getMultipleRecords($query);
            $return = [];

            foreach ($result as $r)
                $return[] = new self(null, $r);

            return $return;

        } catch (Exception $e) { return null; }


    }
    /** @return array of TagElement. All the tags cited by a post. */
    public static function getCitedByPost(int $postid, int $limit = 0){

        try {
            $query = "SELECT * FROM tag AS T INNER JOIN
                       (SELECT C.tagID FROM citazione AS C WHERE C.postID =". $postid ;

            if($limit > 0) $query .= " LIMIT ". $limit;
            $query.= " ) AS C ON T.ID = C.tagID;";

            $result = self::getMultipleRecords($query);

            $return = [];

            foreach ($result as $item)  $return[] = new TagElement(null, $item);

            return $return;

        }catch (Exception $e) { return null; }

    }

    public function getNome(){return $this->nome;}
    public function getLabel(){return $this->label;}

}