<?php
require_once __DIR__ . "/../../Component.php";
require_once __DIR__ . "/../../databaseObjects/user/UserDAO.php";

require_once __DIR__ . "/../followButton/FollowButton.php";

/** User details has button to follow.*/
class UserDetails extends Component {

    private $user;

    private $showButton;
    private $redirect;

    private $displayLimit;

    /** We get user id in input.
     * @param UserVO $user the selected page user.
     * @param string $redirect the page to which we redirect when we follow someone (could be self reference).
     * @param bool $action Se l azione del componente è attiva o meno.
     * @param string|null $HTML the base layout of the component.
     */
    public function __construct(UserVO $user, string $redirect, bool $action = true, string $HTML = null) {

        $HTML = file_get_contents(__DIR__ . "/UserDetails.xhtml");
        $sessionUser = new SessionUser();

        $component = file_get_contents(isset($sessionUser) && $sessionUser->getUser()->getId() === $user->getId() ?
            __DIR__ . "/CambiaImmagineProfilo.xhtml" : __DIR__ . "/ImmagineProfilo.xhtml");


        $HTML = str_replace("<profile-pic />", $component, $HTML);

        parent::__construct($HTML);

        /** Utente di cui visualizzare le informazioni.*/
        $this->user = $user;
        $this->displayLimit = 7;

        /** Bottone di azione e possibilità di attivarlo. */
        $this->showButton = $action;
        if ($this->showButton) $this->redirect = $redirect;

    }

    public function setDisplayLimit($limit) {
        $this->displayLimit = $limit;
    }

    public function resolveData() {

        $resolvedData = [];

        foreach ($this->user->arrayDump() as $key => $value)
            $resolvedData["{" . $key . "}"] = $value;

        return $resolvedData;
    }

    public function build() {

        $html = parent::build();
        $loggedActions = '';

        if ($this->showButton && $this->user->getId() != (new SessionUser())->getUser()->getId())
            $loggedActions = (new FollowButton($this->user, $this->redirect))->build();

        $html = str_replace('<friends />', $this->friendList(), $html);
        return str_replace('<loggedActions />', $loggedActions, $html);

    }

    private function friendList() {

        $userDao = new UserDAO();

        $friendsList = $userDao->getFriends($this->user);
        $friendsListLength = count($friendsList);

        $html = '<h3 id="friends-title"> Amici </h3>';

        /** Empty friend list display. */
        if ($friendsListLength === 0)
            return $html . '<h4> L\' utente non ha ancora amici. </h4>';

        $html .= '<ol class="friends">';

        for ($i = 0; $i < $friendsListLength && $i < $this->displayLimit; $i++) {

            $friendId = $friendsList[$i]->getId();
            $friendName = $friendsList[$i]->getNome();

            $html .= "<li><a href='$this->redirect$friendId'> $friendName </a></li>";
        }

        $usId = $this->user->getId();
        return $html . "</ol> <a href='user_friends.php?id=$usId'> Vedi tutti gli amici. </a>";

    }
}

