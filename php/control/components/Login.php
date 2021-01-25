<?php


    require_once "Component.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "SessionUser.php";

    class Login extends Component {

        private $redirect;
        /* The current session user if exists.*/
        private $user;
        private $loggedNow;

        public function __construct(string $email = null, string $password = null,
                                    string $redirect = 'Location: Home.php',string $HTML = null) {

            parent::__construct ($HTML ?? (file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "login" . DIRECTORY_SEPARATOR . "login.xhtml")));
            $this->redirect = $redirect;

            $this->user = new SessionUser();
            $this->loggedNow = false;

            /** Creazione di nuovo utente di sessione se non è attualemente loggato.*/
            if(!$this->user->userIdentified()){
                /** Controllo sui campi di input.*/
                if(!is_null($email) && !is_null($password)) {

                    $loginUser = (new UserDAO())->getFromLogin($email, $password);
                    /** Se loginUser è valido e ben formato.*/

                    if(!is_null($loginUser->getId()))
                        $this->user->setUserVO($loginUser);

                    /** Anche se fallisce è per sapere che ci ha tentato ora.*/
                    $this->loggedNow = true;
                }
            }

        }

        public function build() {

            if($this->user->userIdentified())  header($this->redirect);

            if(!$this->user->userIdentified() && !$this->loggedNow)
                return $this->baseLayout();

            if(!$this->user->userIdentified() && $this->loggedNow)
                return file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "login" . DIRECTORY_SEPARATOR . "loginFailure.xhtml");

        }

    }