<?php

    require_once __DIR__ . "/../databaseObjects/post/PostDAO.php";
    require_once __DIR__ . "/../databaseObjects/user/UserDAO.php";

    require_once __DIR__ . "/postActions/PostActions.php";
    require_once __DIR__ . "/imageSlideShow/ImagesSlideshow.php";
    require_once __DIR__ . "/comments/Comments.php";

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
            parent::__construct($HTML ?? file_get_contents(__DIR__ . "/Post.xhtml"));

            $this->user = $user;

            $this->post = $post;
            $this->creatorVO = $this->post->getUserVO();

        }

        public function resolveData() {
            return $this->post->arrayDump();
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
                $baseLayout = str_replace("<comments />", "<li>Per leggere o effettuare un commento bisogna aver prima effettuato l'accesso.</li>", $baseLayout);
            }

            return $baseLayout;
        }


    }