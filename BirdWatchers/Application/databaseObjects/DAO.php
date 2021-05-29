<?php

    require_once __DIR__ . "/VO.php";

    abstract class DAO {

    /** Elemento di identificatore id attualmente ricercato.
      * @param $id : identificatore del elemento che va cercato */
        abstract public function get($id);

        /** @return array : di elementi della tipologia di dato cercata.*/
        abstract public function getAll();

        abstract public function checkId(VO &$element) : bool;

        /** Salva l'elemento dato in input nella tabella del db.
          * @param $element : elemento di tipo specifico da aggiungere al db. */
        abstract public function save(VO &$element) : bool;

        /** Crea un elemento tramite la costruzione del messaggio per query.
         * @param array $varDumps
         * @param string $functionName
         * @return array : Ritorna array di dati querati. Se non ha eseguito la creazione ritorna vuoto. */
        protected function performCall(array $varDumps, string $functionName) : array{

            $query = $this->createAction($varDumps, $functionName, 'CALL' );
            $newData = DatabaseAccess::executeSingleQuery($query);
            return isset($newData) && is_array($newData) ? $newData : array('failure'=> true);

        }

        /** Esegue query che non da output ma effettua modifica e non ritorna niente.
         * @param array $varDumps : Le variabili da fornire per la funzione.
         * @param string $functionName : Nome della funzione mysQL da chiamare.
         * @return bool : Vero se la outpu ha avuto successo, falso altrimenti. */
        protected function performNoOutputModifyCall(array $varDumps, string $functionName) : bool{

            $query = $this->createAction($varDumps, $functionName, 'CALL');
            return DatabaseAccess::executeNoOutputUpdateQuery($query);

        }

        protected function performMultiCAll(array $varDumps, string $functionName) : array{
            $query = $this->createAction($varDumps, $functionName, 'CALL' );

            $newData = DatabaseAccess::executeQuery($query);
            return isset($newData) && is_array($newData) ? $newData : array('failure'=> true);
        }

        public function createAction(array $varDumps, string $functionName, string $pre){

            $query = "$pre $functionName(";
            if(!empty($varDumps)) {
                foreach ($varDumps as $param){

                    if(is_bool($param)) $query .= (int) $param . ',';
                    else if(is_string($param)) $query .= "'". str_replace('\'', '\\\'', $param) ."',";
                    else if(is_null($param)) $query .= "null,";
                    else $query .= "$param,";
                }

                $query = substr($query, 0, -1);
            }
            // echo $query .')';
            return $query .');';
        }

        /** Aggiorna un elemento dato in input nella tabella del db.
         *  @param $element : L'elemento da aggiornare. (viene aggiornato comunque)
         *  @param array $params: I diversi parametri da modificare.
         *  @return VO: Nuovo oggetto modificato. */
        public function update(VO &$element, array $params){

            $newData = [];

            foreach($element->varDumps() as $key => $value){
                // Se abbiamo quella chiave in params copio quella.
                if(in_array($key, array_keys($params))) $newData[$key] = $params[$key];
                // Altrimenti ricopio i vecchi dati.
                else $newData[$key] = $value;
            }

            $element = new $element($newData);
            return $element;

        }

        protected function idValid(VO &$element, string $name): bool{

            if(is_null($element->getId())) return false;
            $result = DatabaseAccess::executeSingleQuery("CALL check_$name($element->id);")['idexists'] ?? false;

            // echo $result;

            if(!$result) $element = new $element(null, ...$element->varDumps(true));

            return !is_null($element->getId());
        }

        /** Elmina un elemento del tipo dato.
         * @param VO $element : L'elemento da eliminare. (di tipo DAO)
         * @param string $functionName
         * @return boolean : True se la eliminazione è andata a buon fine.
         */

        public abstract function delete(VO &$element) : bool;

        protected function defaultDelete(VO &$element, string $functionName) : bool{

            $deleted = false;

            if(!is_null($element->getId()))
                $deleted = $this->performNoOutputModifyCall(array($element->id), $functionName);

            if($deleted) // Creazione di nuovo elemento vuoto.
                $element = new $element();

            return $deleted;
        }

    }