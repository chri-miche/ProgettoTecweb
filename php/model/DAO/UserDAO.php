<?php
    require_once __ROOT__.'\model\DAO\DAO.php';
    require_once __ROOT__.'\model\VO\UserVO.php';

    class UserDAO extends DAO {

        public function get($id) {

            $result = $this->performCall(array($id), 'get_user');
            return isset($result['success']) && !$result['success'] ? new UserVO() : new UserVO(...$result);

        }

        public function getAll() {

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_users');
            if( isset($result['success']) && !$result['success']) return $VOArray;

            foreach ($result as $element)  $VOArray [] = new OrdineVO(...$element);

            return $VOArray;

        }

        private function valid(UserVO $element){

            /** Controllo dei parametri necessari per la scrittura sul db.*/
            if (!is_null( $element->getPassword()) && !is_null($element->getEmail()
                    && !is_null($element->getNome()))){

                $query =  is_null($element->getId())
                    ? "CALL check_email_unique_ne('$element->email');"
                    : "CALL check_email_unique('$element->email', $element->id);";

                /* TODO: Exceptions?*/
                return DatabaseAccess::executeSingleQuery($query)['valid'];
            }
            return false;
        }

        public function save(VO &$element) : bool {

            if($this->valid($element)) {

                if($this->checkId($element)){
                    $result = $this->performCall($element->smartDump(true), 'create_user');
                    return !isset($result['failure']);

                } else {

                    $result = $this->performCall($element->smartDump(true), 'update_user');

                    if(!isset($result['failure'])) /* Se non è stato un fallimento.*/
                        $element = new $element(...$result, ...$element->varDumps(true));

                    return !is_null($element->getId());
                }
            }
            /** Statement non valido.*/
            return false;
        }

        public function delete(VO &$element) : bool {
            return $this->defaultDelete($element, 'delete_utente');
        }

        // Specific operations:

        /** @param UserVO $element: Elemento di cui trovare gli amici.
         *  @return null | array(UserVO) : Null se non è possibile la persona abbia amici, e
            * quindi non fa parte del database corrente.*/
        public function getFriends(UserVO &$element){

            /* Se user VO non è settato o non esiste nella banca dati corrente. */
            if(!$this->checkId($element)) return null;

            $query = "CALL get_all_friends($element->id)";
            $userVOArray = []; /** Array di amici. */

            foreach (DatabaseAccess::executeQuery($query) as $element)
                $userVOArray = new UserVO(...$element);

            return $userVOArray;

        }
        /**@param UserVO $element */
        public function checkId(VO &$element) : bool {
            return $this->idValid($element, 'user_id');
        }
    }