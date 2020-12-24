<?php

    require_once __ROOT__ . '\control\components\Component.php';
    /** Should we also give the title importance(hvalue) ? */
    class Title implements Component {

        private $title;
        private $secondTitle;
        private $description;

        public function __construct(string $title, string $secondTitle = null, string $description = null) {

            $this->title = $title;

            $this->secondTitle = $secondTitle;
            $this->description = $description;

        }

        public function build() {
            // TODO: Implement build() method.
            // TODO: Push this stuff in layout and just change values with str_replace
            $HTML = '<div class="w3-card w3-blue w3-padding"; style="width: 80%;display: flex; flex-direction: row; "><h1> '. $this->title .'</h1>';
            if(isset($this->secondTitle))
                $HTML .=  '<h2 class="w3-opacity w3-margin">'. $this->secondTitle.' </h2> ';

            $HTML .= '</div>';

            if(isset($this->description))
                $HTML .= '<div class = "w3-card w3-light-blue w3-padding" style="width: 80%">'. $this->description.'</div>';

            return $HTML;


        }
    }