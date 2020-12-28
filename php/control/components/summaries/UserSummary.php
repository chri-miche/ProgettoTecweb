<?php

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    require_once __ROOT__.'\model\UserElement.php';

    class UserSummary  implements PageFiller {

        private $HTML;

        private $sessionUser;

        private $user;

        public function __construct($id, string $HTML = null){

            $this->HTML = isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\UserSummary.xhtml');

            /** Pagina dell'elemento.*/
            $this->user = new UserElement($id);
            print_r($this->user);

            /** Utente attuale :*/
            $this->sessionUser = new SessionUser();

        }


        function build() {


            /*Here goes a PostPreview.*/
            return $this->HTML;

        }

        public function resolveData() {


        }
    }