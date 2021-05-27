<?php

require_once __DIR__ . "/../Component.php";

require_once __DIR__ . "/../SessionUser.php";
require_once __DIR__ . "/../databaseObjects/user/UserDAO.php";

require_once __DIR__ . "/../siteBar/navigationButton/NavigationButton.php";

require_once __DIR__ . "/../menu/Menu.php";

class SiteBar extends Component {

    private $user;
    /**
     * @var string
     */
    private $position;

    private $value;

    private $voci = [];

    /**
     * @param string $position
     * @param string $defaultSearch
     * @param string|null $HTMLcontent
     */
    public function __construct(string $position, $defaultSearch = '', string $HTMLcontent = null) {

        parent::__construct($HTMLcontent ?? file_get_contents(__DIR__ . "/SiteBar.xhtml"));

        $this->user = new SessionUser();
        $this->position = $position;
        $this->value = $defaultSearch;
    }

    public function build() {

        $baseLayout = $this->baseLayout();
        $baseLayout = str_replace("{value}", $this->value, $baseLayout);


        /** To make code tidied up count the black space of the opened tag before.*/
        if(!$this->user->userIdentified()){

            $contentHTML = file_get_contents(__DIR__ . "/LoggedOutActions.xhtml");

        } else {
            $userVO = $this->user->getUser();
            $contentHTML = file_get_contents(__DIR__ . "/LoggedInActions.xhtml");

            $actions = [
                ['Profilo', 'user_page.php?id={userid}'],
                ['<span xml:lang="en">Logout</span>', 'logout.php'],
                ['Nuovo post', 'newpost.php']
            ];

            if($this->user->getAdmin()) {

                $actions[] = ['Pannello di amministrazione', 'admin.php'];

            }

            $contentHTML = str_replace('<menu />', (new Menu($this->position, $actions)), $contentHTML);

            $contentHTML = str_replace("{username}", $userVO->getNome(), $contentHTML);
            $contentHTML = str_replace("{userid}", $userVO->getId(), $contentHTML);
        }

        $baseLayout = str_replace('<loggedActions />', $contentHTML, $baseLayout);
        $navigation = '';

        if (strcasecmp($this->position, "home") != 0) {
            $navigation = '<a href="index.php" xml:lang="en"> Home </a>';
        }
        if (strcasecmp($this->position, "catalogo") != 0) {
            $navigation .= (new NavigationButton('Catalogo', 'Catalogo.php'))->build();
        }

        $baseLayout = str_replace('<navigation />', $navigation, $baseLayout);
        return $baseLayout;

    }


}