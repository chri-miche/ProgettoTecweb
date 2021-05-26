<?php

require_once __DIR__ . "/../DAO.php";

require_once "OrdineVO.php";
class OrdineDAO extends DAO {

    /** * @inheritDoc  */
    public function get($id) {

        $result = $this->performCall(array($id),'get_ordine');
        return isset($result['failure']) ? new OrdineVO() : new OrdineVO(...$result);

    }

    /** * @inheritDoc  */
    public function getAll() {

        $VOArray = array();

        $result = $this->performMultiCAll(array(), 'get_all_ordini');
        if( isset($result['failure'])) return $VOArray;


        foreach ($result as $element)  $VOArray [] = new OrdineVO(...$element);

        return $VOArray;

    }

    /**@param GenereVO $element.*/
    public function checkId(VO &$element) : bool{
        return $this->idValid($element, 'ordine_id');
    }

    /** * @inheritDoc  */
    public function save(VO &$element) : bool {

        if($this->checkId($element)){

            $result = $this->performNoOutputModifyCall($element->smartDump(),'update_ordine');
            return isset($result['failure']);

        } else {

            $result = $this->performCall($element->smartDump(true), 'create_ordine');

            if(!isset($result['failure']))
                $element = new $element( ...$result, ...$element->varDumps(true));

            return !$element->id === null;

        }
    }

    /** * @inheritDoc  */
    public function delete(VO &$element) : bool{
        return $this->defaultDelete($element, 'delete_ordine');
    }
}