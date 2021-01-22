<?php

    require_once __ROOT__ . '\control\components\Component.php';
    abstract class PageFiller extends Component {

        public function build() {

            $HTML = $this->baseLayout();
            foreach ($this->resolveData() as $key => $value)
                $HTML = str_replace($key, $value, $HTML);
            return $HTML;
        }

    }