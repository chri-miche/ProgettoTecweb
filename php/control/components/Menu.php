<?php
require_once "Component.php";

class Menu extends Component
{
    private static $placeholder = "<voices />";

    private $voices = [];
    public function __construct(string $position, array $voices)
    {
        parent::__construct('<ul class="menu primary-color">' . Menu::$placeholder . '</ul>');

        foreach ($voices as $voice) {
            $this->voices[] = new NavigationButton($voice[0], $voice[1], strpos($voice[1], $position) !== false);
        }
    }

    public function build()
    {
        $htmlVoices = "";
        foreach ($this->voices as $voice) {
            $htmlVoices .= "<li>" . $voice->build() . "</li>";
        }
        return str_replace(Menu::$placeholder, $htmlVoices, parent::build());
    }
}

?>