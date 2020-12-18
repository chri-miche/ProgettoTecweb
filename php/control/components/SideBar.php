<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class SideBar implements Component {

        private $HTML; /** Spostare questo a private in Component e renderla una classe?
                            Cosi da evitare che possa essere modificato una volta inizilizzata.*/
        private $user;

        public function __construct(string $HTMLcontent = null) {

             ($HTMLcontent) ? $this->HTML = $HTMLcontent
                : $this->HTML = file_get_contents(__ROOT__.'\view\modules\SideBar.xhtml');

            $this->user = new SessionUser();

        }

        public function build() {


            $contentHTML = '';

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->getUser()->getId()){

                $contentHTML .= '<li><a href="Login.php">Accedi</a></li>'."\n"
                    .'<li><a href="signup.html">Iscriviti</a></li>';


            } else {

                $contentHTML .= '<li><a href= "Logout.php">Logout</a></li>';

                if($this->user->getUser()->getModerator()){

                    if($this->user->getUser()->getAdmin()){


                    }

                }

            }

            return str_replace(Component::INNER_TAG, $contentHTML, $this->HTML);

        }


    }