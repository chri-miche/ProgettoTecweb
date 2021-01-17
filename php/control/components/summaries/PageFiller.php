<?php

    require_once __ROOT__ . '\control\components\Component.php';
    // TODO: Expain.
    abstract class PageFiller extends Component {

        abstract public function resolveData();

        public function build() {
            // Get parent layout.
            $HTML = $this->baseLayout();

            foreach ($this->resolveData() as $placeholder => $value)
                $HTML = str_replace($placeholder, $value, $HTML);

            return $HTML;
        }

    }