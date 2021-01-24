<?php
    require_once __ROOT__.'\control\components\Component.php';

    require_once __ROOT__ .'\model\DAO\UserDAO.php';


    require_once __ROOT__ .'\control\components\profile\FollowButton.php';

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
        public function __construct(UserVO $user, string $redirect, bool $action = true, string $HTML = null){
            parent::__construct($HTML ?? file_get_contents(__ROOT__.'\view\modules\user\UserDetails.xhtml'));

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

            $resolvedData['{loggedActions}'] = '';

            if($this->showButton && $this->user->getId() != (new SessionUser())->getUser()->getId())
                $resolvedData['{loggedActions}'] = (new FollowButton($this->user,  $this->redirect));

            // TODO: Add modify user button if you are the same as the one displayed and if we need to do so.

            return $resolvedData;

        }
    }