<?php

require_once __DIR__ . "/../../Component.php";

class AdminWelcomePage extends Component {
    public function __construct() {
        parent::__construct(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "AdminWelcomePage.xhtml"));
    }

    public function build() {
        return $this->baseLayout();
    }
}