<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    // TODO: Check this but it should be quite fine right now, it does what it has to
    //  in a very nice way in my opinion. Add exceptions?
    class Login implements Component {
        /* Base page layout.*/
        private $HTML;
        private $redirect;

        /* The current session user if exists.*/
        private $user;

        /* Input fields given.*/
        private $email;
        private $password;

        // TODO: Add session user as parameter by reference and assign it to user.
        public function __construct(string $email = null, string $password = null,
                        string $redirect = 'Location: Home.php',string $HTML = null) {

            $this->HTML = (isset($HTMLcontent)) ? $HTML : (file_get_contents(__ROOT__.'\view\modules\login.xhtml'));
            $this->redirect = $redirect;


            $this->email = $email;
            $this->password = $password;

            $this->user = new SessionUser();

        }

        public function build() {

            if($this->user->userIdentified())  return $this->redirect; // TODO: Add error page?
            if(!isset($this->email) || !isset($this->password))  return $this->HTML;

            $valid = $this->validCredentials();

            if($valid){ header($this->redirect);}

            else if(!($valid === null) && !$valid) { /** Return the login page + error message under the penguin,*/
                return "<div class='form'> <h3>Username/password is incorrect.</h3><br/>
                        Click here to  <a href='login.php'>Login</a> </div>";}
            else {  return file_get_contents(__ROOT__ . '\view\modules\Error.xhtml'); }

        }

        // TODO: Want to make it static? Nah at the moment but maybe one day?
        public function validCredentials() {
            // TODO: Rewrite.
            try {

                $result = UserElement::checkCredentials($this->email, $this->password);

                if ($result) {
                    $this->user->setUser($result);
                    return true;
                }

                return false;
            } catch (Exception $e ){ return null; } // Ritorna nullo e build quindi visualizza una finestra di errore.

        }


    }