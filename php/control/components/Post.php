<?php

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\model\UserElement.php';

    class Post implements Component {

        private $HTML;

        private $user;
        private $post;

        public function __construct(string $HTML = null) {

            ($HTML) ? $this->HTML = $HTML :
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\Post.xhtml');

            $this->user = new SessionUser();

            if(isset($_GET['PostID']))
                $this->post = new PostElement($_GET['PostID']);

        }

        function build() {
            // TODO: Implement build() method.

            if(isset($this->post)) {

                if ($this->post->getId()) {


                    if ($this->user->userIdentified()) {
                        /* He can comment. */
                    }

                }

                return $this->HTML;
            } else {

                /** Redirect to home like youtube does?*/
                return 'Oops you have no post selected.';

            }
        }


        private function postContent(){

        }

        private function commentsContent(){


        }

    }