<?php

require_once __DIR__ . "/../Component.php";
class BirdError extends Component {
    public function __construct(string $HTML, string $message, string $title, string $redirect, int $code) {
        parent::__construct($HTML ?? __DIR__ . "/error.xhtml");
    }
}