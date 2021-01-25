<?php


class FollowButton extends Component{

    private $canOperate;

    private $followerVO; /* Quello che ora segue l altro. */
    private $followedVO; /* Quello che verrà seguito.*/

    private $redirect;

    public function __construct(UserVO $followed, string $redirect, string $HTML = null){

        parent::__construct($HTML ?? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "FollowButton.xhtml"));

        $this->followedVO = $followed; $this->redirect = $redirect;

        $currentUser = new SessionUser();
        $this->followerVO = $currentUser->getUser();

        $this->canOperate = $currentUser->userIdentified() && $this->followerVO->getId() != $this->followedVO->getId();

    }

    public function build() {

        /** If the user is not authenticated or the same as the one displayed we don't show anything.*/
        if(!$this->canOperate)  return '';
        return parent::build();

    }


    public function resolveData(){

        $resolvedData = [];

        $resolvedData['{usid}'] = $this->followedVO->getId();
        $resolvedData['{redirect}'] = $this->redirect;

        $alreadyFollowing = (new UserDAO())->isFollowing($this->followerVO, $this->followedVO);

        $resolvedData['{text}'] = !$alreadyFollowing ? 'Aggiungi ai seguiti' : 'Rimuovi dai seguiti';

        return $resolvedData;

    }

}