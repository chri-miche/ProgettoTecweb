<?php

require_once __DIR__ . "/../Component.php";
require_once __DIR__ . "/../SessionUser.php";

require_once __DIR__. "/../databaseObjects/user/UserDAO.php";
require_once __DIR__. "/../databaseObjects/user/UserVO.php";

class Login extends Component {

    private $user;
    private $loggedNow;

    public function __construct(string $email = null, string $password = null, string $HTML = null) {

        parent::__construct($HTML ?? (file_get_contents(__DIR__ . "/Login.xhtml")));

        $this->user = new SessionUser();
        $this->loggedNow = false;

        if($this->user->userIdentified())
            throw new Exception('User already authenticated');

        /** Creazione di nuovo utente di sessione se non è attualmente logato.*/
        /** Controllo sui campi di input.*/
        if (!is_null($email) && !is_null($password)) {
            $loginUser = (new UserDAO())->getFromLogin($email, $password);
            /** Se loginUser è valido e ben formato.*/
            if (!is_null($loginUser->getId()))
                $this->user->setUserVO($loginUser);
            /** Anche se fallisce è per sapere che ci ha tentato ora.*/
            $this->loggedNow = true;
        }

        if($this->user->userIdentified())
            throw new Exception('User already authenticated');


    }

    public function build() {
        return (!$this->user->userIdentified() && !$this->loggedNow) ? $this->baseLayout():
            file_get_contents(__DIR__ . "/loginFailure.xhtml");
    }

}