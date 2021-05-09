<?php

require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "CommentoDAO.php";
class Comments extends BasePage {

    public function __construct(PostVO $post, SessionUser &$user) {
        parent::__construct("<component />");

        $commentVOArray = (new CommentoDAO)->getAllOfPost($post->getId());

        foreach ($commentVOArray as $commentVO)
            $this->addComponent(new
            Class($commentVO->arraydump(), file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "Comment.xhtml")) extends Component {

                private $commentData;

                public function __construct(array $data, string $HTML) {
                    parent::__construct($HTML); $this->commentData = $data;
                }

                public function resolveData() {
                    $resolvedData = [];
                    foreach ($this->commentData as $key =>$value) $resolvedData["{".$key."}"] = $value;
                    return $resolvedData;
                }
        });

        $data = array();

        $data["{content_id}"] = $post->getId();
        $data["{id_utente}"] = $user->getUser()->getId();

        $this->addComponent(new class($data, file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "InsertComment.xhtml")) extends Component {

            private $data;

            public function __construct(array $data, string $HTML) {
                parent::__construct($HTML); $this->data = $data;
            }

            public function resolveData() {
                return $this->data;
            }
        });
    }

}