<?php


class Register implements Component {

    private $user;

    private $HTML;
    private $redirect;

    /** Credentials must be null to make the page update.
     * @param null $username : Username of the new user.
     * @param null $password : Password of he new user.
     * @param null $email : Email of the new user.
     * @param string $redirect : Page which to redirect to if already autenticated.
     * @param string|null $HTML : Page layout.
     */
    public function __construct($username = null, $password = null, $email = null,
        string $redirect = 'Location: Home.php', string $HTML = null) {

        $this->HTML = isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Register.xhtml');
        //if(!isset($this->HTML)) throw new Exception('No layout for component'); // TODO: Move it away? And just display nothing?

        $this->redirect = $redirect;

        $this->user = new SessionUser();

        if(isset($username, $password, $email)){ /** Checks if user exists.*/
            $res = UserElement::addUser($username,$password,$email);
            if(!($res === null)) $this->user->setUser($res); /* If it does not create new and assing it to the session.*/
        }
    }

    public function build() {

        if($this->user->userIdentified())  header($this->redirect);
        // Or add error page? Error page that displays the
        // error message: You cannot register if you are already autenticated. Could be an idea.
        return $this->HTML;

     }
}