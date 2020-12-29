<?php

    require_once __ROOT__.'\control\components\Component.php';
    class Error extends Component {

        // TODO: Rocha tocca a te

        public function __construct(string $HTML){

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.''));

        }

        public function build() {
            // TODO: Implement build() method.

        }


    }