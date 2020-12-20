<?php

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\model\UserElement.php';

    class Post implements Component {

        private $HTML;

        private $user;

        private $post;
        private $creator;

        /** TODO: Throw exception if we construct a non valid post? Yes myabe we shouòd.
         * @param string|null $HTML */
        public function __construct(string $HTML = null) {

            ($HTML) ? $this->HTML = $HTML :
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\Post.xhtml');

            $this->user = new SessionUser();

            /** TODO :Brutto, trovare un modo migliore epr farlo */
            if(isset($_GET['PostID'])) {
                $this->post = new PostElement($_GET['PostID']);

                if($this->post->exists())
                    $this->creator = new UserElement($this->post->getCreator());

                else
                    $this->post = null;
            }

        }

        function build() {
            /* Se il post è assegnato. */
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
                return 'Oops you have no post selected.';
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