<?php


class TagElement extends Element
{

    protected function loadData() {
        // TODO: Implement loadData() method.
        try{
            if($this->ID === null)
                throw new Exception('Cannot retrieve data of element not 
                defined yet. First define the element.');

            $query = "  SELECT Q.nome, L.text as label  FROM label AS L RIGHT JOIN
                        (SELECT T.ID, T.LabelID, T.nome FROM tag AS T WHERE T.ID = ". $this->ID
                        ." LIMIT 1) AS Q ON L.ID = Q.LabelID LIMIT 1;";

            $res = $this->getSingleRecord($query);

            if(!isset($res['label'])) $res['label'] = "";

            return $res;

        } catch (Exception $e) { return null; }
    }

    /** * @inheritDoc  */
    static public function checkID($id){
        // TODO: Implement checkID() method.
        try {

            $query = "SELECT T.ID  FROM Tag AS T  WHERE T.ID = ". $id. " LIMIT 1; ";
            return !(self::getSingleRecord($query) === null);

        } catch (Exception $e) { return false; }
    }

    public function getNome(){return $this->nome;}
    public function getLabel(){return $this->label;}

}