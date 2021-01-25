<?php

require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "summaries" . DIRECTORY_SEPARATOR . "PageFiller.php";

class NavigationButton extends PageFiller {

    private $data;
    private $disabled;

    public function __construct($text, $link, $disabled = false) {
        parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "browsing" . DIRECTORY_SEPARATOR . "NavigationButton.xhtml"));

        $this->data = array("{text}" => $text, "{link}" => $link);
        $this->disabled = $disabled;

    }

    public function build() {
        $HTML = parent::build();

        if ($this->disabled)
            $HTML = str_replace('href="' . $this->data["{link}"] . '"', 'class="disabled"', $HTML);

        return $HTML;

    }

    public function resolveData() {
        return $this->data;
    }
}