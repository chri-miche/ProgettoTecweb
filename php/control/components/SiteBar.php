<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';
    require_once __ROOT__.'\control\components\browsers\NavigationButton.php';

    require_once __ROOT__ . '\model\DAO\UserDAO.php';

    class SiteBar extends Component {

        private $user;
        /*** @var string */
        private $position;

        /** TODO: Give user as parameter by reference? Avoid multiple definitons of SessionUser.
         * @param string|null $HTMLcontent
         * @param string $position */
        public function __construct(string $position, string $HTMLcontent = null) {

            parent::__construct( $HTMLcontent ?? file_get_contents(__ROOT__.'\view\modules\SiteBar.xhtml'));

            $this->user = new SessionUser();
            $this->position = $position;

        }

        public function build() {


            $baseLayout = $this->baseLayout();

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->userIdentified()){

                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedOutActions.xhtml');

            } else {

                $userVO = $this->user->getUser();

                $contentHTML = file_get_contents(__ROOT__.'\view\modules\LoggedInActions.xhtml');

                $contentHTML = str_replace("{username}", $userVO->getNome(), $contentHTML);
                $contentHTML = str_replace("{userid}", $userVO->getId(), $contentHTML);

                if($userVO->isAdmin())
                    $adminButton = new NavigationButton('Admin', 'Admin.php');

                $newPostButton = new NavigationButton('Nuovo Post', 'NewPost.php');

            }

            $baseLayout = str_replace('<loggedActions />', $contentHTML, $baseLayout);
            $navigation = '';

            if (strcasecmp($this->position, "home") != 0) {
                $navigation = '<a href="Home.php" xml:lang="en"> Home </a>';
            }
            if (strcasecmp($this->position, "catalogo") != 0) {
                $navigation .= (new NavigationButton('Catalogo', 'Catalogo.php'))->build();
            }
            if (strcasecmp($this->position, "admin") != 0 && isset($adminButton)) {
                $navigation .= $adminButton->build();
            }
            if (strcasecmp($this->position, "newpost") != 0 && isset($newPostButton)) {
                $navigation .= $newPostButton->build();
            }

            $baseLayout = str_replace('<navigation />', $navigation, $baseLayout);
            return $baseLayout;

        }


    }