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
            $this->post = new PostElement($_GET['PostID']);

        }

        function build() {
            // TODO: Implement build() method.

            if($this->post->getId()){




                if($this->user->userIdentified()){
                    /* He can comment. */
                }

            }

            return $this->HTML;

        }


        private function postContent(){

        }

        private function commentsContent(){


        }

    }