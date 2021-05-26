<?php

require_once __DIR__ . "/../Component.php";
require_once __DIR__ . "/../SessionUser.php";

class Login extends Component {

    private $redirect;
    /* The current session user if exists.*/
    private $user; private $loggedNow;

    public function __construct(string $email = null, string $password = null,
                                string $redirect = 'Location: index.php',string $HTML = null) {

        parent::__construct ($HTML ?? (file_get_contents(__DIR__ . "/Login.xhtml")));
        $this->redirect = $redirect;

        $this->user = new SessionUser();
        $this->loggedNow = false;

        /** Creazione di nuovo utente di sessione se non è attualmente logato.*/
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
            return file_get_contents(__DIR__ . "/loginFailure.xhtml");

    }

}