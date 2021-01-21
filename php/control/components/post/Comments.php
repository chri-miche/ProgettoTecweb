<?php


class Comments extends BasePage
{
    public function __construct(PostElement $post, SessionUser &$user)
    {
        parent::__construct("<component />");

        $result = DatabaseAccess::executeQuery("select * from commento c join contenuto cm on c.contentID = cm.ID join utente u on u.ID = cm.UserID where postID = '" . $post->getId() . "';");

        foreach ($result as $row) {

            $data = array();
            foreach ($row as $key => $value) {
                $data["{" . $key . "}"] = $value;
            }

            $this->addComponent(new class(file_get_contents(__ROOT__ . '/view/modules/post/Comment.xhtml'), $data) extends Component {

                private $row;

                public function __construct(string $HTML, $row)
                {
                    parent::__construct($HTML);
                    $this->row = $row;
                }

                public function resolveData()
                {
                    return $this->row;
                }
            });
        }

        $data = array();
        $data["{contentID}"] = $post->getData()["contentID"];
        $data["{idUtente}"] = $user->getUser()->getData()['ID'];

        $this->addComponent(new class(file_get_contents(__ROOT__ . '/view/modules/post/InsertComment.xhtml'), $data) extends Component {

            private $data;

            public function __construct(string $HTML, $data)
            {
                parent::__construct($HTML);
                $this->data = $data;
            }

            public function resolveData()
            {
                return $this->data;
            }
        });
    }

}