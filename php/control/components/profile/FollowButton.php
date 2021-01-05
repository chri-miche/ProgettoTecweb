<?php


class FollowButton extends Component{

    private $currentUser;

    private $friend;
    private $redirect;

    public function __construct(int $id, string $redirect, string $HTML = null){

        parent::__construct(isset($HTML)? $HTML : file_get_contents(__ROOT__.'\view\modules\user\FollowButton.xhtml'));

        $this->friend = $id;
        $this->redirect = $redirect;

        $this->currentUser = new SessionUser();

    }

    public function build() {

        /** If the user is not authenticated or the same as the one displayed we don't show anything.*/
        if(!$this->currentUser->userIdentified() || $this->currentUser->getUser()->ID == $this->friend)  return null;

        $baseLayout = $this->baseLayout();

        foreach ($this->resolveData() as $key => $value)
            $baseLayout = str_replace($key, $value, $baseLayout);

        return $baseLayout;

    }


    public function resolveData(){

        $resolvedData = [];

        $resolvedData['{usid}'] = $this->friend;
        $resolvedData['{redirect}'] = $this->redirect;

        $resolvedData['{add}'] = !in_array($this->friend, $this->currentUser->getUser()->amici);

        $resolvedData['{text}'] = $resolvedData['{add}'] ? 'Aggiungi ai seguiti' : 'Rimuovi dai seguiti.';

        return $resolvedData;

    }

}