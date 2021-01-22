<?php


    require_once __ROOT__.'\control\components\generics\LinkRow.php';
    require_once __ROOT__.'\control\components\profile\UserSide.php';


    require_once __ROOT__.'\control\components\summaries\PageFiller.php';

    require_once __ROOT__.'\model\UserElement.php';
    require_once __ROOT__.'\model\TagElement.php';

    // TODO: Make the user summary smaller, it's huge now.
    // TODO: Lo user summary sarà una BasePage che contiene molti componenti. Dfinirli. (sidetags side thing etc)
    class UserSummary  extends PageFiller {

        private $sessionUser;

        private $user;

        private $action;

        private $selfReference;
        private $tagReference;

        public function __construct($id, string $selfReference, string $tagReference = 'Search.php', string $HTML = null, string $tagLayoutHTML = null){

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\UserSummary.xhtml'));

            /** Pagina dell'elemento.*/
            $this->user = new UserElement($id);
            // print_r($this->user);

            /** Utente attuale :*/
            $this->sessionUser = new SessionUser();

            // References to other pages via link.
            $this->selfReference = $selfReference;
            $this->tagReference = $tagReference;

        }


        function build() {

            $baseLayout = $this->baseLayout();

            // Se utente esiste mostriamo la sua pagina.
            if($this->user->exists()) {
                // Fields swap;
                foreach ($this->resolveData() as $key => $value)
                    $baseLayout = str_replace($key, $value, $baseLayout);

                // Posts created by the user.
                // TODO : add posts created by user as previews.

            } // Mostriamo 404 not found se utente non esiste.

            return $baseLayout;

        }

        public function resolveData() {

            $swapData = [];

            foreach ($this->user->getData() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;

            $swapData['{tipologiaUtente}'] = self::getRole($swapData['{isAdmin}'], $swapData['{moderator}']);



            // Followed friends list.
            $swapData['{users}'] = !($this->user->amici == []) ? self::makeFriendsList($this->user->amici, $this->selfReference) : 'L utente non ha amici.';


            // Followed tags list.
            $swapData['{tags}'] = !($this->user->interests == []) ? self::makeTagsList($this->user->interests, $this->tagReference) : 'L utente non segue tag';


            // Logged actions.
            // Autenticated actions. (if autenticated and not same user you can follow)
            $swapData['{loggedActions}'] = $this->loggedActions();


            // Posts created by the user.
            // TODO : add posts created by user as previews.


            return $swapData;

        }

        public static function getRole(bool $admin, bool $moderator){

            return $moderator ? $admin ?    '<div id="role" style="width: 100%; padding: 1em;"> Amministratore </div>'
                : '<div id="role" style="width: 100%; padding: 1em"> Moderatore </div>' : null;

        }

        private static function makeFriendsList(array $ids, string $reference){

            $friendsList = '';

            foreach ($ids as $id) {

                $friend = new UserElement($id);
                $friendsList .= (new LinkRow($reference .'?id='. $friend->ID, $friend->nome, $friend->immagineProfilo))->build();

            }

            return $friendsList;

        }

        // TODO: Avoid single queries and make one full?
        private static function makeTagsList(array $ids, string $reference){

            $tagsList = '';

            foreach ($ids as $id) {

                $tag = new TagElement($id);
                $tagsList .= (new LinkRow($reference. '?id='. $tag->ID, $tag->nome))->build();
            }

            return $tagsList;

        }

        // TODO: Move to outer file?
        // TODO: Follow component? -> YES FOLLOW COMPONETN
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

                    $loggedLayout .=   '<form action="FollowUser.php?previousPath='. $this->selfReference .'&usid='. $this->user->ID .'&add='. $add .'" method="post">
                                            
                                            <input type="hidden" value="'.$this->selfReference .' name= "previousPath" />
                                            <input type="hidden" value="'.$this->user->ID.'" name ="usid">
                                            <input type="hidden" value="'.$add.'" name ="add">
                                       
                                            <button type="submit"> '. $follow .' </button>
                                       
                                        </form>';

                }


            }

            return $loggedLayout;

        }

    }