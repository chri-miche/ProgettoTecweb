<?php

/** Non vogliamo venga istanziata vero?*/
// No in quanto non è un'entità forte e quindi un vero elemento del DB
class Citazione extends Element
{

    protected function loadData() {
        // TODO: Implement loadData() method.
    }

    /**
     * @inheritDoc
     */
    static public function checkID($id) {
        // TODO: Implement checkID() method.
    }


    // TODO: Consider if to put in each element, do we need this class?
    static public function postRelated($pid){
        /** Ogni tag citato da un post. */

        $query = "SELECT C.tagID as ID FROM Citazione AS C WHERE C.postID = ". $pid .";";
        // TODO : Fix, it gives array to array.
        $ret = array();

        $res =  Element::getMultipleRecords($query);
        foreach ($res as $re){
            $ret[] = $re['ID'];
        } return $ret;


    }

    static public function tagRelated($tid){

        /** Ogni post citato da un Tag.*/

    }
}