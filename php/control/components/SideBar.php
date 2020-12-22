<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class SideBar implements Component {

        private $HTML; /** Spostare questo a private in Component e renderla una classe?
                        Cosi da evitare che possa essere modificato una volta inizilizzata.*/

        private $user;

        /** TODO: Give user as parameter by reference? Avoid multiple definitons of SessionUser. */
        public function __construct(string $HTMLcontent = null) {

             ($HTMLcontent) ? $this->HTML = $HTMLcontent
             : $this->HTML = file_get_contents(__ROOT__.'\view\modules\SideBar.xhtml');

            $this->user = new SessionUser();

        }

        public function build() {


            $contentHTML = '';

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->getUser()->getId()){

                $contentHTML .= '<a href="Login.php" class="w3-bar-item w3-button" style="width: 100%">Accedi</a>'."\n"
                    .'<a href="signup.html" class="w3-border-top w3-border-bottom w3-bar-item w3-button" style="width: 100%">Iscriviti</a>';


            } else {

                $contentHTML .= '<a class="w3-bar-item w3-button w3-border-bottom" style="width: 100%" href= "Logout.php">Logout</a>';

                if($this->user->getUser()->getModerator()){

                    if($this->user->getUser()->getAdmin()){


                    }

                }

            }

            $contentHTML = str_replace(Component::INNER_TAG, $contentHTML, $this->HTML);
            return $contentHTML;

        }


    }