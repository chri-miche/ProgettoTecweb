<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    // TODO: Check this but it should be quite fine right now, it does what it has to
    //  in a very nice way in my opinion. Add exceptions?
    class Login implements Component {
        /* Base page layout.*/
        private $HTML;

        /* The current session user if exists.*/
        private $user;

        /* Input fields given.*/
        private $email;
        private $password;

        // TODO: Add session user as parameter by reference and assign it to user.
        public function __construct(string $email = null, string $password = null, string $HTMLcontent = null) {

            ($HTMLcontent) ? $this->HTML = $HTMLcontent : $this->HTML =(
                file_get_contents(__ROOT__.'\view\modules\login.xhtml'));


            $this->email = $email;
            $this->password = $password;

            $this->user = new SessionUser();

        }

        public function build() {

            if(!isset($this->email) && !isset($this->password))
                return $this->HTML;

            $valid = $this->validCredentials();

            if(!($valid === null) && $valid){ header('Location: Home.php');}
            else if(!($valid === null) && !$valid) {
                return "<div class='form'> <h3>Username/password is incorrect.</h3><br/>
                        Click here to  <a href='login.php.old'>Login</a> </div>";}
            else {  return file_get_contents(__ROOT__ . '\view\modules\Error.xhtml'); }

        }


        public function validCredentials(){

            if(!$this->user->userIdentified()){
                if(isset($this->email) && isset($this->password)){

                    // TODO: Rifare il modello, non mi piace così.
                    $res = $this->user->getUser()->checkCredentials($this->email, $this->password);

                    if(!($res === null) && !($res === false)) {
                        $this->user->setUser($res); return true;
                    } return false;

                }

            } else

                return null;

        }

    }