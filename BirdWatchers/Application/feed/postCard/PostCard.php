<?php

    require_once __DIR__ . "/../../databaseObjects/user/UserDAO.php";
    require_once __DIR__ . "/../../databaseObjects/post/PostDAO.php";

    class PostCard extends PageFiller {

        private $postVO;

        public function __construct($postId) {
            parent::__construct(file_get_contents(__DIR__ . "/PostCard.xhtml"));
            // Get the post VO.
            $this->postVO = (new PostDAO())->get($postId);
    }

    public function resolveData() {

        $ritorno = [];

        foreach ($this->postVO->arrayDump() as $key => $value) {
            if ($key == 'immagine') {
                $ritorno['{immagine}'] = str_replace('\\', '/', $value);;
            } else {
                $ritorno['{' . $key . '}'] = $value;
            }
        }


        $likes = (new PostDAO())->getLikes($this->postVO);

        $ritorno['{likes}'] = $likes;

        return $ritorno;
    }
}