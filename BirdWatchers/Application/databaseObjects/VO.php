<?php
    /** @VO rappresenta un elemento di database manipolabile.
     * dotato di : costruttore, setter e getter. (proprio semplicemente dati).*/
    require_once __DIR__ . "/DatabaseAccess.php";
    interface  VO {

        /** Ottieni elemento del campo dato in input.
         *  @param $name : Nome del campo da selezionare in selezione magica.
         *  @return value | null : Valore del campo se esiste altrimenti nullo.*/
        public function __get($name);

        /** Ritorna tutti i campi dell oggetto come array. In oggetti complessi è necessario
         *  che il contenitore aggiunga i campi del figlio in modo oppurtuno.*/
        public function arrayDump() : array;

        public function varDumps(bool $id = false) : array;
        public function smartDump(bool $id = false) : array;

    }