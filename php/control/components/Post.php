<?php

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\model\UserElement.php';
    require_once __ROOT__.'\model\meta\Persistent.php';
    require_once __ROOT__.'\control\components\post\PostActions.php';
    require_once __ROOT__.'\control\components\post\ImagesSlideshow.php';
    require_once __ROOT__.'\control\components\post\Comments.php';

    //TODO: Remove globals inside of Post and other Components.
    // TODO: Move to be postSummary. And also create CommentSummary.
    class Post extends Component {

        /** @var $user SessionUser Current user */
        private $user;
        private $postUser;

        private $post;
        private $creator;

        private $interpol = null;

        /***
         * @param int $pid
         * @param SessionUser $user
         * @param string|null $HTML
         */
        public function __construct(int $pid, SessionUser &$user, string $HTML = null) {

            $this->user = $user;

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Post.xhtml'));

            if(isset($pid) && PostElement::checkID($pid)){

                $this->post = new PostElement($pid);
                $this->postUser = new UserElement($this->post->UserID);

            }

            // print_r($this->post);
            // print_r($this->postUser);

            // print_r(UserElement::getInterestsIds($this->postUser->ID));

        }

        public function resolveData()
        {
            if (!isset($this->interpol)) {
                $ref = $this->post->getData();
                $this->interpol = array();
                foreach ($ref as $key => $value) {
                    $this->interpol['{' . $key . '}'] = $value;
                }
                $this->interpol['{nome}'] = $this->postUser->getData()['nome'];
                $this->interpol['{UserID}'] = $this->postUser->getData()['ID'];
            }
            return $this->interpol;
        }

        function build()
        {
            $html = parent::build();
            $images = (new ImagesSlideshow($this->interpol['{contentID}']))->build();

            $html = str_replace("<images />", $images, $html);

            if ($this->user->userIdentified()) {
                $postActions = (new PostActions($this->post, $this->user))->build();
                $html = str_replace("<actions />", $postActions, $html);

                $comments = (new Comments($this->post, $this->user))->build();
                $html = str_replace("<comments />", $comments, $html);
            } else {
                $html = str_replace("<comments />", "<li>Per commentare bisogna aver effettuato l'accesso.</li>", $html);
            }

            return $html;
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