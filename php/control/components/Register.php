<?php


    class Register extends Component {

        private $user;
        private $redirect;

        /** Credentials must be null to make the page update.
         * @param null $username : Username of the new user.
         * @param null $password : Password of he new user.
         * @param null $email : Email of the new user.
         * @param string $redirect : Page which to redirect to if already autenticated.
         * @param string|null $HTML : Page layout.
         */
        public function __construct(
            UserVO $user,
            $username = null, $password = null, $email = null,
            string $redirect = 'Location: Home.php', string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Register.xhtml'));


            $this->redirect = $redirect;
            $this->user = new SessionUser();

            if(isset($username, $password, $email)){ /** Checks if user exists.*/
                $res = new UserDAO();
                $res = UserElement::addUser($username,$password,$email);
                if( !$res->save($user)) $this->user->setUser($res); /* If it does not create new and assing it to the session.*/
            }
        }

        public function build() {

            if($this->user->userIdentified()) header($this->redirect);

            // Or add error page? Error page that displays the
            // error message: You cannot register if you are already autenticated. Could be an idea.

            return $this->baseLayout();

         }
    }