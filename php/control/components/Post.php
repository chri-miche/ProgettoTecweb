<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "PostDAO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "meta" . DIRECTORY_SEPARATOR . "Persistent.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "PostActions.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "ImagesSlideshow.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "Comments.php";

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
            parent::__construct($HTML ?? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "Post.xhtml"));

            $this->user = $user;

            $this->post = $post;
            $this->creatorVO = $this->post->getUserVO();

        }

        public function resolveData() {

            $resolvedData = $this->post->arrayDump();
            $likes = (new PostDAO())->getLikes($this->post);

            $resolvedData['likes'] = $likes >= 0 ? "+$likes" : "$likes";
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