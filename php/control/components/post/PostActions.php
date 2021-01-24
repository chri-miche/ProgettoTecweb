<?php


class PostActions extends Component {

    private $postVO;
    private $userLiked;

    public function __construct(PostVO $postVO, SessionUser &$utente) {

        parent::__construct(file_get_contents(__ROOT__.'/view/modules/post/PostActions.xhtml'));

        $this->postVO = $postVO;
        $this->userLiked = (new UserDAO())->likes($this->postVO, $utente->getUser());
    }

    public function resolveData() {

        $resolvedData = [];

        $resolvedData["{id}"] = $this->postVO->getId();

        /** User liked or hasn't liked the post.*/

        if(is_null($this->userLiked)) {

            $resolvedData['{hasLiked}'] = 'canLike';
            $resolvedData['{hasDisliked}'] = 'canDislike';

        } else {

            if($this->userLiked){

                $resolvedData['{{hasLiked}'] = 'canLike';
                $resolvedData['{hasDisliked}'] = 'disliked';

            } else {

                $resolvedData['{hasLiked}'] = 'like';
                $resolvedData['{{hasDisliked}'] = 'canDislike';

            }
        }

        $numberLikes =  (new PostDAO())->getLikes($this->postVO);
        $resolvedData['{likes}'] = $numberLikes >= 0 ? "+$numberLikes" : "$numberLikes";

        return $resolvedData;
    }
}