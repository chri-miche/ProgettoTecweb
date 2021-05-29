<?php

require_once __DIR__ . "/../DAO.php";
class TagDAO extends DAO {

    /**
     * @inheritDoc */
    public function get($id) {

        $result = $this->performCall(array($id), 'get_tag');
        return isset($result['failure']) ? new TagVO() : new TagVO(...array_values($result));

    }

    /**
     * @inheritDoc
     */
    public function getAll() {

        $VOArray = array();

        $result = $this->performMultiCAll(array(), 'get_all_tag');
        if( isset($result['failure'])) return $VOArray;


        foreach ($result as $element)  $VOArray [] = new TagVO(...array_values($element));

        return $VOArray;
    }

    public function checkId(VO &$element): bool {
        return $this->idValid($element, 'tag_id');
    }

    /**
     * @inheritDoc
     */
    public function save(VO &$element): bool {

        if($this->checkId($element)){

            $result = $this->performNoOutputModifyCall($element->smartDump(),'update_tag');
            return !isset($result['failure']);

        } else {

            $result = $this->performCall($element->smartDump(true), 'create_tag');

            if(!isset($result['failure']))
                $element = new $element( ...array_values($result), ...$element->varDumps(true));

            return !is_null($element->getId());
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(VO &$element): bool {
        return $this->defaultDelete($element, 'delete_tag');
    }
}