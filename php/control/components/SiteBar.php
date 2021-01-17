<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';
    require_once __ROOT__.'\control\components\browsers\NavigationButton.php';

    class SiteBar extends Component {

        private $HTML; /** Spostare questo a private in Component e renderla una classe?
                        Cosi da evitare che possa essere modificato una volta inizilizzata.*/

        private $user;
        /**
         * @var string
         */
        private $position;

        /** TODO: Give user as parameter by reference? Avoid multiple definitons of SessionUser.
         * @param string|null $HTMLcontent
         * @param string $position
         */
        public function __construct(string $position, string $HTMLcontent = null) {

            parent::__construct(isset($HTMLcontent) ? $HTMLcontent : file_get_contents(__ROOT__.'\view\modules\SiteBar.xhtml'));
            $this->user = new SessionUser();

            $this->position = $position;

        }

        public function build() {


            $baseLayout = $this->baseLayout();

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->getUser()->getId()){

                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedOutActions.xhtml');

            } else {
                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedInActions.xhtml');
                $username = $this->user->getUser()->nome;
                $userid = $this->user->getUser()->ID;
                $contentHTML = str_replace("{username}", $username, $contentHTML);
                $contentHTML = str_replace("{userid}", $userid, $contentHTML);

                if($this->user->getUser()->getModerator()){

                    if($this->user->getUser()->getAdmin()){


                    }

                }

            }

            $baseLayout = str_replace('<loggedActions />', $contentHTML, $baseLayout);
            $navigation = '';

            if (strcasecmp($this->position, "home") != 0) {
                $navigation = '<a href="Home.php" xml:lang="en"> Home </a>';
            }
            if (strcasecmp($this->position, "ordine") != 0) {
                $navigation .= (new NavigationButton('Catalogo', 'Ordine.php', false))->build();
            }
            // da qui in poi .=

            $baseLayout = str_replace('<navigation />', $navigation, $baseLayout);
            return $baseLayout;

        }


    }