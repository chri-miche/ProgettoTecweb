<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';
    require_once __ROOT__.'\control\components\browsers\NavigationButton.php';

    require_once __ROOT__ . '\model\DAO\UserDAO.php';

    class SiteBar extends Component {

        private $user;
        /**
         * @var string
         */
        private $position;

        private $value;

        /** TODO: Give user as parameter by reference? Avoid multiple definitons of SessionUser.
         * @param string $position
         * @param string $defaultSearch
         * @param string|null $HTMLcontent
         */
        public function __construct(string $position, $defaultSearch = '', string $HTMLcontent = null) {

            parent::__construct($HTMLcontent ?? file_get_contents(__ROOT__.'\view\modules\SiteBar.xhtml'));

            $this->user = new SessionUser();
            $this->position = $position;
            $this->value = $defaultSearch;
        }

        public function build() {


            $baseLayout = $this->baseLayout();

            $baseLayout = str_replace("{value}", $this->value, $baseLayout);


            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->userIdentified()){

                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedOutActions.xhtml');

            } else {
                $userVO = $this->user->getUser();
                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedInActions.xhtml');

                $contentHTML = str_replace("{username}", $userVO->getNome(), $contentHTML);
                $contentHTML = str_replace("{userid}", $userVO->getId(), $contentHTML);

                if($this->user->getAdmin()) {

                    $adminButton = new NavigationButton('Admin', 'Admin.php');
                    $contentHTML = str_replace('<admin />', $adminButton->build(), $contentHTML);

                }
            }

            $baseLayout = str_replace('<loggedActions />', $contentHTML, $baseLayout);
            $navigation = '';

            if (strcasecmp($this->position, "home") != 0) {
                $navigation = '<a href="Home.php" xml:lang="en"> Home </a>';
            }
            if (strcasecmp($this->position, "catalogo") != 0) {
                $navigation .= (new NavigationButton('Catalogo', 'Catalogo.php'))->build();
            }

            $baseLayout = str_replace('<navigation />', $navigation, $baseLayout);
            return $baseLayout;

        }


    }