<?php
require_once __ROOT__ .'\control\components\profile\FollowButton.php';
/** User details has button to follow.*/
class UserDetails extends Component {

    private $user;
    private $redirect;

    /** We get user id in input.
     * @param UserElement $user the selected page user.
     * @param string $redirect the page to which we redirect when we follow someone (could be self reference).
     * @param string|null $HTML the base layout of the component.
     */
    public function __construct(UserElement $user, string $redirect, string $HTML = null){
        parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\user\UserDetails.xhtml'));

        $this->user = clone $user;
        $this->redirect = $redirect;

    }

    public function build() {

        $baseLayout = $this->baseLayout();

        foreach ($this->resolveData() as $key => $value)
            $baseLayout = str_replace($key, $value, $baseLayout);

        return $baseLayout;

    }

    public function resolveData(){

        $resolvedData = [];

        foreach ($this->user->getData() as $key => $value)
            if(!is_array($value)) $resolvedData['{'.$key.'}'] = $value;

        // TODO: Add modify user button if you are the same as the one displayed.
        $resolvedData['{loggedActions}'] = (new FollowButton($this->user->ID, $this->redirect))->returnComponent();


        return $resolvedData;

    }
}