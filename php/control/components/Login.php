<?php

    require_once __ROOT__.'\control\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class Login extends Component {

        private $user;
        private const MOD_TAG = '<contents />'; // Sposto in Component?

        public function __construct(string $HTMLcontent = null) {

            parent::__construct($HTMLcontent);
            $this->user = new SessionUser();

        }

        protected function addContent() {

            if(!$this->user->getUser()->getId()) { /** If not user identified*/

                $var['html'] = file_get_contents(__ROOT__ . '\view\modules\Login.xhtml');

                if(isset($_POST['username'])){

                    $email = stripslashes($_REQUEST['username']);
                    $password = stripslashes($_REQUEST['password']);

                    $sessionUser = $this->user->getUser();
                    $res = $sessionUser->checkCredentials($email,$password);

                    if($res){

                        $this->user->getUser()->loadElement($res['ID']);
                        $_SESSION['User'] = serialize($this->user->getUser());

                        header('Location: Home.php');

                    } else {

                        $var['html'] ="<div class='form'>
                                <h3>Username/password is incorrect.</h3><br/>
                                    Click here to 
                                <a href='login.php.old'>Login</a>
                                </div>";
                    }

                }

            } else {

                $var['html'] = file_get_contents(__ROOT__ . '\view\modules\Error.xhtml');

            }
            $var['tag'] = self::MOD_TAG;


            return $var;
        }

        public function validForThisBuild(string $HTML) {
            // TODO: Implement validForThisBuild() method.
            return true;
        }

        public function newBuild(string $HTML) {
            // TODO: Implement newBuild() method.
        }

        public function deleteBuild() {
            // TODO: Implement deleteBuild() method.
        }
    }