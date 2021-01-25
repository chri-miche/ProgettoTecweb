<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "PostDAO.php";

    class PostCard extends PageFiller {

        private $postVO;

        public function __construct($postId) {
            parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "feed" . DIRECTORY_SEPARATOR . "PostCard.xhtml"));
            // Get the post VO.
            $this->postVO = (new PostDAO())->get($postId);
    }

    public function resolveData() {

        $ritorno = [];

        foreach ($this->postVO->arrayDump() as $key => $value)
            $ritorno['{' . $key . '}'] = $value;

        return $ritorno;
    }
}