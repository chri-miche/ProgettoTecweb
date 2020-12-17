<?php

    require_once __ROOT__.'\control\Component.php';
    class SearchBar extends Component {

        const MOD_TAG = '<searchBar />';

        public function __construct(string $HTMLcontent = null) {

            parent::__construct($HTMLcontent);

        }

        protected function addContent() {

            $var['html'] = file_get_contents(__ROOT__.'\view\modules\SearchBar.xhtml');
            $var['tag'] = self::MOD_TAG;

            return $var;
        }

        public function validForThisBuild(string $HTML) {
            // TODO: Implement validForThisBuild() method.
            return true;
        }

        public function newBuild(string $HTML) {

        }

        public function deleteBuild() {
            // TODO: Implement deleteBuild() method.
        }
    }