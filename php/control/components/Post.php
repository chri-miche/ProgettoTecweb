<?php

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\model\UserElement.php';

    //TODO: Remove globals inside of Post and other Components.
    // TODO: Move to be postSummary. And also create CommentSummary.
    class Post implements Component {

        private $HTML;

        private $user;

        private $post;
        private $creator;

        /*** @param string|null $HTML */
        public function __construct(int $pid = null, SessionUser &$user, string $HTML = null) {

            $this->HTML = (isset($HTML)) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Post.xhtml');


            if(isset($pid) && PostElement::checkID($pid)){

                $this->post = new PostElement($pid);

                $this->user = new UserElement($this->post->UserID);

            }

            print_r($this->post);
            print_r($this->user);

            print_r(UserElement::getInterestsIds($this->user->ID));

        }

        function build() {
            /* Se il post è assegnato significa che esiste e abbiamo tutti i dati utili. */
            if(isset($this->post)) {

                return $this->HTML;

                $ret = "<div class='w3-container' style='width: 70%'>";


                $ret .= self::posterData($this->creator);

                $ret .= $this->post->content;

                $ret .= $this->post->getTitle();
                $ret .= '<br></br>';

                $postData = $this->post->getPictures();

                foreach ($postData as $image)
                    $ret .=  '<img src="'. $image .'" ></img>' ;


                if ($this->user->userIdentified()) {
                    /* He can comment. */
                }


                return $ret.= "</div>";

            } else {
                /** Redirect to home like youtube does?*/
                return '<div class="w3-container w3-red w3-margin w3-border" style="width: 60%"> Oops you have no post selected.</div>';
            }
        }

        /** Ausliari per dividere la costruzione di un post.*/

        private static function posterData(UserElement $user){
                $ret = "";
                /* strReplace may be the way to go but we try by builing it from hand.*/
                $ret .= '<header class="w3-container w3-blue"><h1>';

                $ret .= '<img src= "'. $user->immagine . '"></img>';
                $ret .= 'Creato da :' .$user->nome . "  ";

                return $ret."</h1></header>";

        }

        private function postContent(){

        }

        private function commentsContent(){


        }

    }