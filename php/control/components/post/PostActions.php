<?php


class PostActions extends Component
{

    private $data;

    public function __construct(PostElement $post, SessionUser &$utente)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/post/PostActions.xhtml'));
        $this->data = array();
        $this->data["{contentID}"] = $post->getData()["contentID"];
        $this->data["{idUtente}"] = $utente->getUser()->getData()['ID'];



        $liked = DatabaseAccess::executeSingleQuery("select likes from approvazione where contentID = '" .
            $post->getData()["contentID"] .
            "' and utenteID ='" .
            $utente->getUser()->getData()['ID'] .
            "';");

        if (empty($liked)) {
            $likes = "0";
        } else {
            $likes = $liked['likes'];
        }

        if ($likes == "1") {
            $this->data['{likeAction}'] = 'liked';
            $this->data['{dislikeAction}'] = 'canDislike';
        } elseif ($likes == "-1") {
            $this->data['{likeAction}'] = 'canLike';
            $this->data['{dislikeAction}'] = 'disliked';
        } else {
            $this->data['{likeAction}'] = 'canLike';
            $this->data['{dislikeAction}'] = 'canDislike';
        }

    }

    public function resolveData()
    {
        return $this->data;
    }
}