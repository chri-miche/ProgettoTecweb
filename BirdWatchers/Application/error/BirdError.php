<?php

require_once __DIR__ . "/../Component.php";
class BirdError extends Component {

    private $data;

    public function __construct(?string $HTML, string $message, string $title, string $redirect, int $code) {

        parent::__construct($HTML ?? file_get_contents(__DIR__ . "/error.xhtml"));
        $this->data = array('{title}' => $title, '{description}' => $message, '{code}' => $code, '{redirect}'=> $redirect);
    }

    public function resolveData(){
        return $this->data;
    }
}