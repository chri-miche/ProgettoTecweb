<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class Login implements Component {

        private $HTML;
        private $user;

        public function __construct(string $HTMLcontent = null) {

            if($HTMLcontent)
                $this->HTML = $HTMLcontent;

            else
                $this->HTML =(file_get_contents(__ROOT__.'\view\modules\login.xhtml'));

            $this->user = new SessionUser();

        }

        public function build() {

            if(!$this->user->userIdentified()) { /** If not user identified*/

                $this->HTML = file_get_contents(__ROOT__ . '\view\modules\Login.xhtml');

                if(isset($_POST['username'])){

                    $email = stripslashes($_REQUEST['username']);
                    $password = stripslashes($_REQUEST['password']);

                    $sessionUser = $this->user->getUser();
                    $res = $sessionUser->checkCredentials($email,$password);

                    if($res){

                        $this->user->setUser($res['ID']);
                        $_SESSION['User'] = serialize($this->user->getUser());

                        header('Location: Home.php');

                    } else {

                        $this->HTML ="<div class='form'>
                                <h3>Username/password is incorrect.</h3><br/>
                                    Click here to 
                                <a href='login.php.old'>Login</a>
                                </div>";
                    }

                }

            } else {

                return file_get_contents(__ROOT__ . '\view\modules\Error.xhtml');

            }

            return $this->HTML;
        }

    }