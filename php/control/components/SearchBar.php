<?php

    // TODO: Implement
    require_once __ROOT__.'\control\components\Component.php';
    class SearchBar extends Component {


        public function __construct(string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\SearchBar.xhtml'));

        }

        function build() {

            return $this->baseLayout();

        }
    }