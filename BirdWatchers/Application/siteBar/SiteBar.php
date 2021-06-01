<?php

require_once __DIR__ . "/../Component.php";

require_once __DIR__ . "/../SessionUser.php";
require_once __DIR__ . "/../databaseObjects/user/UserDAO.php";

require_once __DIR__ . "/../siteBar/navigationButton/NavigationButton.php";
require_once __DIR__ . "/../menu/Menu.php";

class SiteBar extends Component {

    private $user;
    /*** @var string */
    private $position;

    private $value;

    /**
     * @param string $position
     * @param string $defaultSearch
     * @param string|null $HTMLContent
     */
    public function __construct(string $position, $defaultSearch = '', string $HTMLContent = null) {

        parent::__construct($HTMLContent ?? file_get_contents(__DIR__ . "/SiteBar.xhtml"));

        $this->user = new SessionUser();
        $this->position = $position;
        $this->value = $defaultSearch;
    }

    public function build() {

        $baseLayout = $this->baseLayout();
        $baseLayout = str_replace("{value}", $this->value, $baseLayout);


        /** To make code tidied up count the black space of the opened tag before.*/
        if (!$this->user->userIdentified()) {
            $contentHTML = file_get_contents(__DIR__ . "/LoggedOutActions.xhtml");
        } else {
            $userVO = $this->user->getUser();
            $contentHTML = file_get_contents(__DIR__ . "/LoggedInActions.xhtml");

            $actions = [
                ['Catalogo', 'catalogo.php'],
                ['Profilo', 'user_page.php?id={userid}'],
                ['<span xml:lang="en" lang="en">Logout</span>', 'logout.php'],
                ['Nuovo post', 'new_post.php']
            ];

            if ($this->user->getAdmin()) {
                $actions[] = ['Pannello di amministrazione', 'admin.php'];
            }

            $contentHTML = str_replace('<menu />', (new Menu($this->position, $actions)), $contentHTML);

            $contentHTML = str_replace("{username}", $userVO->getNome(), $contentHTML);
            $contentHTML = str_replace("{userid}", $userVO->getId(), $contentHTML);
        }

        $baseLayout = str_replace('<loggedActions />', $contentHTML, $baseLayout);

        $homeButton = '';

        if (strcasecmp($this->position, "index") != 0)
            $homeButton = '<a href="index.php" xml:lang="en" lang="en"> Home </a>';

        $baseLayout = str_replace('<home-button />', $homeButton, $baseLayout);
        return $baseLayout;

    }


}