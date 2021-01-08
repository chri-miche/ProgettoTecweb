<?php

    require_once __ROOT__ . '\control\components\Component.php';

    class Title extends PageFiller {

        private $data;

        public function __construct(string $title, string $secondTitle = null, string $description = null, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Title.xhtml'));

            $this->data = array(
                "{title}" => $title,
                "{subtitle}" => $secondTitle,
                "{description}" => $description,
            );

        }

        public function build() {
            // TODO: Push this stuff in layout and just change values with str_replace
            $HTML = $this->baseLayout();

            foreach ($this->data as $key => $value) {
                $HTML = str_replace($key, $value, $HTML);
            }

            return $HTML;

            /*
            $HTML = '<div class="w3-card w3-blue w3-padding "; style="width: 80%;display: flex; flex-direction: row; align-self: center; margin-top: 20px"><h1> '. $this->title .'</h1>';
            if(isset($this->secondTitle))
                $HTML .=  '<h2 class="w3-opacity w3-margin">'. $this->secondTitle.' </h2> ';

            $HTML .= '</div>';

            if(isset($this->description))
                $HTML .= '<div class = "w3-card w3-light-blue w3-padding" style="width: 80%; align-self: center">'. $this->description.'</div>';

            return $HTML;*/


        }

        public function resolveData()
        {

        }
    }