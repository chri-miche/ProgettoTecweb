<?php
    /** @VO rappresenta un elemento di database manipolabile.
     * dotato di : costruttore, setter e getter. (proprio semplicemente dati).*/
    require_once __ROOT__.'\model\DatabaseAccess.php';
    interface  VO {

        /** Ottieni elemento del campo dato in input.
         *  @param $name : Nome del campo da selezionare in selezione magica.
         *  @return value | null : Valore del campo se esiste altrimenti nullo.*/
        public function __get($name);

        public function varDumps(bool $id = false) : array;

        public function smartDump(bool $id = false) : array;

    }