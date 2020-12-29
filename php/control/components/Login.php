<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    // TODO: Check this but it should be quite fine right now, it does what it has to
    //  in a very nice way in my opinion. Add exceptions?
    class Login extends Component {

        private $redirect;

        /* The current session user if exists.*/
        private $user;
        /* The given input fields.*/
        private $email; private $password;

        // TODO: Add session user as parameter by reference and assign it to user.
        public function __construct(string $email = null, string $password = null,
                        string $redirect = 'Location: Home.php',string $HTML = null) {

            parent::__construct ((isset($HTML)) ? $HTML : (file_get_contents(__ROOT__.'\view\modules\login.xhtml')));

            $this->redirect = $redirect;

            $this->email = $email;
            $this->password = $password;

            $this->user = new SessionUser();

        }


        public function build() {

            $builtComponent = $this->baseLayout();

            if($this->user->userIdentified())  header($this->redirect);
            if(!isset($this->email) || !isset($this->password))  return $builtComponent;

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