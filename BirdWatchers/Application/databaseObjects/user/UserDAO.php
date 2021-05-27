<?php
    require_once __DIR__ . "/../DAO.php";
    require_once __DIR__ . "/UserVO.php";

    class UserDAO extends DAO {

        public function get($id) {

            $result = $this->performCall(array($id), 'get_user');
            return isset($result['failure']) ? new UserVO() : new UserVO(...array_values($result));

        }

        public function getAll() {

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_users');
            if(isset($result['failure'])) return $VOArray;

            foreach ($result as $element)
                $VOArray [] = new UserVO(...array_values($element));

            return $VOArray;

        }
        public function getFromLogin(string $email, string $password) : UserVO{

            $result = $this->performCall(array($email, $password), 'get_user_from_login');
            return isset($result['failure'])?  new UserVO() : new UserVO(...array_values($result));

        }


        private function valid(UserVO $element){
            /** Controllo dei parametri necessari per la scrittura sul db.*/
            if (!is_null( $element->getPassword()) && !is_null($element->getEmail() && !is_null($element->getNome()))){

                $result = is_null($element->getId())
                    ?$this->performCall(array($element->getEmail()), 'check_email_unique_ne')
                    :$this->performCall(array($element->getEmail(),$element->getId()), 'check_email_unique');

                return isset($result['valid']) && !$result['valid'];
            }

            return false;
        }

        public function save(VO &$element) : bool {

            if($this->valid($element)) {

                if($this->checkId($element)){

                    $result = $this->performNoOutputModifyCall($element->smartDump(), 'update_user');
                    return !isset($result['failure']);

                } else {

                    $result = $this->performCall($element->smartDump(true), 'create_user');

                    if(!isset($result['failure'])) /* Se non è stato un fallimento.*/
                        $element = new $element(...array_values($result), ...$element->varDumps(true));

                    return !is_null($element->getId());
                }
            }

            return false;
        }

        public function likes(PostVO $postVO, UserVO $userVO) {

            $result = $this->performCall(array($postVO->getId(), $userVO->getId()), 'check_user_likes_post');
            if(isset($result['failure'])) return null;
            return isset($result['likes']) && $result['likes'];

        }


        public function follow(UserVO $user, UserVO $friend) : bool{

            $result = $this->performNoOutputModifyCall(array($user->getId(), $friend->getId()), 'user_act_friend');
            return !isset($result['failure']);

        }

        public function isFollowing(UserVO $user, UserVO $friend) : bool{

            $result = $this->performCall(array($user->getId(), $friend->getId()), 'user_is_following');
            return !isset($result['failure']) && $result['follow'];

        }

        public function delete(VO &$element) : bool {
            return $this->defaultDelete($element, 'delete_utente');
        }

        // Specific operations:

        /** @param UserVO $element: Elemento di cui trovare gli amici.
         *  @return null | array(UserVO) : Null se non è possibile la persona abbia amici, e
            * quindi non fa parte del database corrente.*/
        public function getFriends(UserVO &$element) : array{

            $VOArray = []; /** Array di amici. */
            /* Se user VO non è settato o non esiste nella banca dati corrente. */
            if(!$this->checkId($element)) return $VOArray;

            $result = $this->performMultiCAll(array($element->getId()), 'get_all_friends');

            if(!isset($result['failure']))
                foreach ($result as $element)
                    $VOArray [] = new UserVO(...array_values($element));

            return $VOArray;

        }

        /**@param UserVO $element */
        public function checkId(VO &$element) : bool {
            return $this->idValid($element, 'user_id');
        }
    }