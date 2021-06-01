<?php

require_once "LandingPage.php";

class SuccessLandingPage extends LandingPage {
    public function __construct($manage) {
        parent::__construct($manage, 'Operazione completata con successo', 'success');
    }
}