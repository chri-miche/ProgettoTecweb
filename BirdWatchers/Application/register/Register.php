<?php

    require_once __DIR__ . "/../Component.php";
    require_once __DIR__ . "/../SessionUser.php";

    class Register extends Component {

        private $user; private $invalid;
        private $redirect;

        /** Credentials must be null to make the page update.
         * @param null|string $username : Username of the new user.
         * @param null|string $password : Password of he new user.
         * @param null|string $email : Email of the new user.
         * @param string $redirect : Page which to redirect to if already autenticated.
         * @param string|null $HTML : Page layout. */
        public function __construct(?string $username = null, ?string $password = null, ?string $email = null,
            string $redirect = 'Location: index.php', string $HTML = null) {

            parent::__construct($HTML ?? file_get_contents(__DIR__ . "/Register.xhtml"));

            $this->invalid = false;

            $this->redirect = $redirect;
            $this->user = new SessionUser();

            if(!is_null($username) && !is_null($password) && !is_null($email)){ /** Checks if user exists.*/

                $userDAO = new UserDAO();
                $newUser = new UserVO(null, $username, $email, $password);

                if($userDAO->save($newUser)) {
                    /** Save fa side effect su user e quindi ottiene il suo id nuovo.*/
                    /** Assegna un nuovo utente. {@see SessionUser} */
                    $this->user->setUserVO($newUser);
                } else {
                    /** Show credenziali non validi.*/
                    $this->invalid = true;
                }

            }
        }

        public function build() {

            if($this->user->userIdentified())
                header($this->redirect);

            $html = $this->baseLayout();
            if($this->invalid) {/* Errore nelle credenziali. */
                $html = str_replace('{email_error}', 'Email già registrata nel nostro sito.', $html);
            } else {
                $html = str_replace('{email_error}', '', $html);
            }

            return $html;

         }
    }
?>