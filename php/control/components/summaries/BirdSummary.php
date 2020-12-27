<?php
    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\model\BirdElement.php';

    class BirdSummary implements Component {

        /** Bird data element, the join of all his parents. (maximum data) */
        private $bird;
        private $HTML;

        public function __construct($id, string $HTML = null){

            $this->HTML = file_get_contents(__ROOT__.'\view\modules\BirdSummary.xhtml');

            $bird = new BirdElement(35);
            print_r($bird);


        }

        function build() {
            // TODO: Implement build() method.
            return $this->HTML;

        }
    }