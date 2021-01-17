<?php


require_once __ROOT__.'\control\components\Component.php';
class AdminWelcomePage extends Component
{

    public function __construct()
    {
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\admin\AdminWelcomePage.xhtml'));
    }

    public function build()
    {
        return $this->baseLayout();
    }
}