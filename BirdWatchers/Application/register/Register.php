<?php

require_once __DIR__ . "/../Component.php";
require_once __DIR__ . "/../SessionUser.php";

class Register extends Component {

    private $user;
    private $invalid;


    /** Credentials must be null to make the page update.
     * @param null|string $username : Username of the new user.
     * @param null|string $password : Password of he new user.
     * @param null|string $email : Email of the new user.
     * @param string|null $HTML : Page layout.
     */
    public function __construct(?string $username = null, ?string $password = null, ?string $email = null, string $HTML = null) {

        parent::__construct($HTML ?? file_get_contents(__DIR__ . "/Register.xhtml"));

        $this->invalid = false;
        $this->user = new SessionUser();

        if ($this->user->userIdentified())
            throw new Exception('User già autenticato');

        if (!is_null($username) && !is_null($password) && !is_null($email)) {
            /** Checks if user exists.*/

            $userDAO = new UserDAO();
            $newUser = new UserVO(null, $username, $email, $password);

            if ($userDAO->save($newUser)) $this->user->setUserVO($newUser);
            else $this->invalid = true;
        }
    }

    public function build() {
        return str_replace('{email_error}', $this->invalid ? 'Email già registrata nel nostro sito' : '', $this->baseLayout());
    }

    public function registered() {
        return $this->user->userIdentified();
    }
}