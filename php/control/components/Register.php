<?php


class Register implements Component {

    private $user;

    public function __construct($username = null, $password = null, $email = null) {

        $this->user = new SessionUser();

        if(isset($username, $password, $email)){ /** Checks if user exists.*/
            $res = UserElement::addUser($username,$password,$email);
            if(!($res === null)) $this->user->setUser($res); /* If it does not create new and assing it to the session.*/
        }


    }

    public function build() {
         // TODO: Implement build() method.
        $HTML = file_get_contents(__ROOT__.'\view\modules\Register.xhtml');

        if($this->user->userIdentified()){
            return 'Already autenticated';

        }

        return $HTML;

     }
}