<?php

require_once "ConservazioneVO.php";

class ConservazioneDAO extends DAO {
    /** @var $defaultDAO */
    static private $defaultDAO;


    public function __construct(){
        if(!isset(ConservazioneDAO::$defaultDAO))
            ConservazioneDAO::$defaultDAO = $this->get('RM'); /** Rischio minimo.*/
    }

    /**
     * @inheritDoc */
    public function get($id) : ConservazioneVO {

        $result = $this->performCall(array($id), 'get_conservazione');
        return isset($result['failure']) ? new ConservazioneVO() : new ConservazioneVO(...array_values($result));

    }

    /**
     * @inheritDoc */
    public function getAll() : array {

        $VOArray = array();

        $result = $this->performMultiCAll(array(), 'get_all_conservazione');
        if(isset($result['failure'])) return $VOArray;

        foreach ($result as $element)  $VOArray [] = new ConservazioneVO(...array_values($element));

        return $VOArray;

    }

    /** Potremmo rendere parte di qeusto alla radice. @
     * @param ConservazioneVO $element */
    public function checkId(VO &$element) : bool {

        if(is_null($element->getId())) return false;
        /** Ritorna vero se id esiste. Falso se id non esiste. (non va eleminato in quanto conservazione non ha
         un id auto incrementato ma definito da utente.*/
        $result = DatabaseAccess::executeSingleQuery("CALL check_conservazione_id('$element->id');");
        return isset($result['idexists']) && $result['idexists'];

    }

    /**
     * @inheritDoc *
     * @param ConservazioneVO $element */
    public function save(VO &$element) : bool {

        if($this->checkId($element)){

            $result = $this->performNoOutputModifyCall($element->smartDump(), 'update_conservazione');
            return !isset($result['failure']);

        } else {

            $result = $this->performCall($element->smartDump(), 'create_conservazione');
            /* Ritorna vero se è stato costruito l oggetto falso altrimenti.*/
            return !isset($result['failure']);
        }
    }

    /**
     * @inheritDoc */
    public function delete(VO &$element) : bool {

        $deleted = false;

        if(!is_null($element->id) && $element->id != ConservazioneDAO::$defaultDAO->getId())
            $deleted = $this->performNoOutputModifyCall(array($element->id), 'delete_conservazione');

        /** L oggetto corrente viene svuotato.*/
        if($deleted) $element = new $element();

        return $deleted;
    }

    public static function getDefault(){ return clone ConservazioneDAO::$defaultDAO; }
}
