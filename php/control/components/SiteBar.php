<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class SiteBar implements Component {

        private $HTML; /** Spostare questo a private in Component e renderla una classe?
                        Cosi da evitare che possa essere modificato una volta inizilizzata.*/

        private $user;

        /** TODO: Give user as parameter by reference? Avoid multiple definitons of SessionUser. */
        public function __construct(string $HTMLcontent = null) {

             ($HTMLcontent) ? $this->HTML = $HTMLcontent
             : $this->HTML = file_get_contents(__ROOT__.'\view\modules\SiteBar.xhtml');

            $this->user = new SessionUser();

        }

        public function build() {


            $contentHTML = '';

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->getUser()->getId()){

                $contentHTML .= '<a href="Login.php" class="w3-bar-item w3-button" style="width: 10%; margin-left: auto;">Accedi</a>'."\n"
                    .'<a href="Register.php" class="w3-border-top w3-border-bottom w3-bar-item w3-button" style="width: 10%">Iscriviti</a>';


            } else {
                // TODO Move it somewhere else, it's ugly here.
                $contentHTML .= '<div class="w3-dropdown-hover" style="margin-left: auto"><button class="w3-button">'.$this->user->getUser()->nome.'</button>
                                 <div class="w3-dropdown-content w3-bar-block w3-card-4" style="z-index: 5000;">
                                 <a href="UserPage.php?='. $this->user->getUser()->ID . '"class="w3-bar-item w3-button"> Profilo </a>
                                 <a href="Logout.php" class="w3-bar-item w3-button"> Logout </a> </div> </div>';

                if($this->user->getUser()->getModerator()){

                    if($this->user->getUser()->getAdmin()){


                    }

                }

            }

            $contentHTML = str_replace(Component::INNER_TAG, $contentHTML, $this->HTML);
            return $contentHTML;

        }


    }