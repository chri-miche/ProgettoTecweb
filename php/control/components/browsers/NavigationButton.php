<?php


class NavigationButton extends PageFiller {

    private $data;
    private $disabled;

    public function __construct($text, $link, $disabled) {
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\browsing\NavigationButton.xhtml'));

        $this->data = array(
            "{text}" => $text,
            "{link}" => $link);

        $this->disabled = $disabled;
    }

    public function build()
    {
        $HTML = parent::build();
        if ($this->disabled) {
            $HTML = str_replace('href="' . $this->data["{link}"] . '"', 'class="disabled"', $HTML);
        }
        return $HTML;

    }

    public function resolveData()
    {
        return $this->data;
    }
}