<?php

    require_once __ROOT__.'\model\DAO\PostDAO.php';
    require_once __ROOT__.'\model\DAO\UserDAO.php';

    require_once __ROOT__.'\model\meta\Persistent.php';

    require_once __ROOT__.'\control\components\post\PostActions.php';
    require_once __ROOT__.'\control\components\post\ImagesSlideshow.php';
    require_once __ROOT__.'\control\components\post\Comments.php';

    class Post extends Component {

        /** @var $user SessionUser Current user */
        private $user;


        private $post;
        private $creatorVO;

        /***
         * @param PostVO $post
         * @param SessionUser $user
         * @param string|null $HTML */
        public function __construct(PostVO &$post, SessionUser &$user, string $HTML = null) {
            parent::__construct($HTML ?? file_get_contents(__ROOT__.'\view\modules\Post.xhtml'));

            $this->user = $user;

            $this->post = $post;
            $this->creatorVO = $this->post->getUserVO();

        }

        public function resolveData() {

            $resolvedData = $this->post->arrayDump();
            $resolvedData['likes'] = (new PostDAO())->getLikes($this->post);

            return $resolvedData;
        }

        function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace("{".$key."}", $value, $baseLayout);

            /** Immagini dei post.*/
            $images = (new ImagesSlideshow($this->post->getId()))->build();
            $baseLayout = str_replace("<images />", $images, $baseLayout);

            if ($this->user->userIdentified()) {

                $postActions = (new PostActions($this->post, $this->user))->build();
                $baseLayout = str_replace("<actions />", $postActions, $baseLayout);

                $comments = (new Comments($this->post, $this->user))->build();
                $baseLayout = str_replace("<comments />", $comments, $baseLayout);

            } else {
                $baseLayout = str_replace("<comments />", "<li>Per commentare bisogna aver effettuato l'accesso.</li>", $baseLayout);
            }

            return $baseLayout;
        }


    }