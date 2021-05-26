<?php


require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";
class AdminWelcomePage extends Component
{

    public function __construct()
    {
        parent::__construct(file_get_contents("AdminWelcomePage.xhtml"));
    }

    public function build()
    {
        return $this->baseLayout();
    }
}