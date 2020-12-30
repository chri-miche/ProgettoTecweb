<?php


    require_once __ROOT__.'\control\components\generics\LinkRow.php';

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    require_once __ROOT__.'\model\UserElement.php';

    class UserSummary  extends PageFiller {

        private $sessionUser;

        private $user;

        private $action;
        private $selfReference;

        public function __construct($id, string $selfReference, bool $action = null, string $HTML = null){
            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\UserSummary.xhtml'));

            /** Pagina dell'elemento.*/
            $this->user = new UserElement($id);
            print_r($this->user);

            /** Utente attuale :*/
            $this->sessionUser = new SessionUser();

            $this->action = $action;
            $this->selfReference = $selfReference;

        }


        function build() {

            $baseLayout = $this->baseLayout();
            // Se utente esiste mostriamo la sua pagina.
            if($this->user->exists()) {


                // Fields swap;
                foreach ($this->resolveData() as $key => $value)
                    $baseLayout = str_replace($key, $value, $baseLayout);


                // Followed friends list.
                $friendList = !($this->user->amici === []) ? self::makeFriendsList($this->user->amici): 'L utente non ha amici.';
                $baseLayout = str_replace('{users}', $friendList, $baseLayout);

                // Followed tags list.
                $tagsList = !($this->user->interests === []) ? self::makeTagsList($this->user->tags) : ' L utente non segue alcun tag';
                $baseLayout = str_replace('{tags}', $tagsList, $baseLayout);

                // Posts created by the user.
                // TODO : add posts created by user as previews.

                // TODO: Autenticated actions. (if autenticated and not same user you can follow)

                $baseLayout = str_replace('{loggedActions}', $this->loggedActions(), $baseLayout);


            } // Mostriamo 404 not found se utente non esiste.

            return $baseLayout;

        }

        public function resolveData() {

            $swapData = [];

            foreach ($this->user->getData() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;

            $swapData['{tipologiaUtente}'] = self::getRole($swapData['{isAdmin}'], $swapData['{moderator}']);

            return $swapData;

        }

        public static function getRole(bool $admin, bool $moderator){

            return $moderator ? $admin ?    '<div id="role" style="width: 100%; padding: 1em;"> Amministratore </div>'
                : '<div id="role" style="width: 100%; padding: 1em"> Moderatore </div>' : null;

        }

        private static function makeFriendsList(array $ids){

            $friendsList = '';

            foreach ($ids as $id) {

                $friend = new UserElement($id);
                $friendsList .= (new LinkRow($friend->ID, $friend->nome, $friend->immagineProfilo))->build();

            }

            return $friendsList;

        }

        // TODO: Avoid single queries and make one full?
        private static function makeTagsList(array $ids){

            $tagsList = '';

            foreach ($ids as $id) {

                $tag = new TagElement($id);
                $tagsList .= (new LinkRow($tag->ID, $tag->nome))->build();
            }

            return $tagsList;

        }

        // TODO: Move to outer file?
        private function loggedActions(){

            $loggedLayout = '';

            if($this->sessionUser->userIdentified()){
                /** If logged user is the same as of the page (if profile is his)*/
                $loggedUserData = $this->sessionUser->getUser();

                if($loggedUserData->ID == $this->user->ID){

                    // Modifica profilo sarà una pagina in cui si può modificare la password e immagine di profilo e username.
                    $loggedLayout .= '<a href=""> Modifica profilo </a>';


                } else {

                    $add = !in_array($this->user->ID, $this->sessionUser->getUser()->amici);
                    $follow = $add ? 'Segui' : 'Smetti di seguire';

                    $loggedLayout .=   '<a class="w3-button w3-black" href=\php\view\pages\FollowUser.php?previousPath='. $this->selfReference .'&usid='. $this->user->ID .'&add='. $add .'> '. $follow .' </a>';

                }


            }

            return $loggedLayout;

        }

    }