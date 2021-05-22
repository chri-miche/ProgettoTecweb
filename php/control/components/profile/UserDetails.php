<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";


    require_once "FollowButton.php";

    /** User details has button to follow.*/
    class UserDetails extends Component {

        private $user;

        private $showButton;
        private $redirect;

        /** We get user id in input.
         * @param UserVO $user the selected page user.
         * @param string $redirect the page to which we redirect when we follow someone (could be self reference).
         * @param bool $action Se l azione del componente è attiva o meno.
         * @param string|null $HTML the base layout of the component. */
        public function __construct(UserVO $user, string $redirect, bool $action = true, string $HTML = null) {

            $HTML = file_get_contents(__ROOT__."" . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "UserDetails.xhtml");
            $sessionUser = new SessionUser();
            if (isset($sessionUser) && $sessionUser->getUser()->getId() === $user->getId()) {
                $component = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "CambiaImmagineProfilo.xhtml");
            } else {
                $component = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "ImmagineProfilo.xhtml");
            }
            $HTML = str_replace("<profile-pic />", $component, $HTML);

            parent::__construct($HTML);

            /** Utente di cui visualizzare le informazioni.*/
            $this->user = $user;

            /** Bottone di azione e possibilità di attivarlo. */
            $this->showButton = $action;
            if($this->showButton) $this->redirect = $redirect;

        }

        public function resolveData(){

            $resolvedData = [];

            foreach ($this->user->arrayDump() as $key => $value)
                $resolvedData["{".$key."}"] = $value;

            return $resolvedData;
        }

        public function build()
        {
            $html = parent::build();
            $loggedActions = '';
            if($this->showButton && $this->user->getId() != (new SessionUser())->getUser()->getId())
                $loggedActions = (new FollowButton($this->user,  $this->redirect))->build();

            return str_replace('<loggedActions />', $loggedActions, $html);
        }
    }