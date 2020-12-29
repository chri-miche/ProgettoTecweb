<?php


    require_once __ROOT__.'\control\components\generics\LinkRow.php';

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    require_once __ROOT__.'\model\UserElement.php';

    class UserSummary  implements PageFiller {

        private $HTML;

        private $sessionUser;

        private $user;

        public function __construct($id, string $HTML = null){ // TODO: Get error template too?

            $this->HTML = isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\UserSummary.xhtml');

            /** Pagina dell'elemento.*/
            $this->user = new UserElement($id);
            print_r($this->user);

            /** Utente attuale :*/
            $this->sessionUser = new SessionUser();

        }


        function build() { // TODO: Make  PageFiller abstract and implement this?

            // Se utente esiste mostriamo la sua pagina.
            if($this->user->exists()) {

                // Fields swap;
                foreach ($this->resolveData() as $key => $value)
                    $this->HTML = str_replace($key, $value, $this->HTML);


                $friendList = !($this->user->amici === []) ? self::makeFriendsList($this->user->amici): 'L utente non ha amici.';
                $this->HTML = str_replace('{users}', $friendList, $this->HTML);

                // TODO: The same for the tags. ( The tags might not be displayed in rows but flexed to fit.)

                // TODO: Autenticated actions. (if autenticated and not same user you can follow)

            } // Mostriamo 404 not found se utente non esiste.

            return $this->HTML;

        }

        public function resolveData() {

            $swapData = [];

            foreach ($this->user->getData() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;

            $swapData['{tipologiaUtente}'] = self::getRole($swapData['{isAdmin}'], $swapData['{moderator}']);

            return $swapData;

        }

        public static function getRole(bool $admin, bool $moderator){

            return $moderator ? $admin ? 'Amministratore' : 'Moderatore' : 'Utente';

        }

        private static function makeFriendsList(array $ids){

            $friendsList = '';

            /* For every friends we create a new line of the list.*/
            foreach ($ids as $id) {

                $friend = new UserElement($id);
                $friendsList .= (new LinkRow($friend->ID, $friend->nome, $friend->immagineProfilo))->build();

            }

            return$friendsList;

        }
    }