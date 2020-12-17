<?php

    require_once __ROOT__.'\control\Component.php';
    require_once __ROOT__.'\control\SessionUser.php';

    class SideBar extends Component {

        private $user;

        private const MOD_TAG = '<sideBar />'; // Sposto in Component?
        private const INNER_MOD_TAG = '<loggedActions \>';



        public function __construct(string $HTMLcontent = null) {

            parent::__construct($HTMLcontent);
            $this->user = new SessionUser();

        }

        public function validForThisBuild(string $HTML) {

            return !(strpos($HTML, self::MOD_TAG) == false);

        }

        protected function addContent() {

            $ret['html'] = file_get_contents(__ROOT__.'\view\modules\SideBar.xhtml');
            $ret['tag'] = self::MOD_TAG;


            $contentHTML = '';

            /** To make code tidied up count the black space of the opened tag before.*/
            if(!$this->user->getUser()->getId()){

                $contentHTML .= '<li><a href="Login.php">Accedi</a></li>'."\n"
                    .'<li><a href="signup.html">Iscriviti</a></li>';


            } else {

                $contentHTML .= '<li><a href= "Logout.php">Logout</a></li>';

                if($this->user->getUser()->getModerator()){

                    if($this->user->getUser()->getAdmin()){


                    }

                }

            }

            $ret['html'] = str_replace(self::INNER_MOD_TAG, $contentHTML, $ret['html']);

            return $ret;

        }


        public function newBuild(string $HTML) {

        }

        public function deleteBuild()
        {
            // TODO: Implement deleteBuild() method.
        }

    }