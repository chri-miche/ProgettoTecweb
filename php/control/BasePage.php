<?php

    require_once __ROOT__.'\control\components\Component.php';

    //TODO: In page building we have multple possible components this means that
    // it's hard to unserstand the h1, h2 etc stuff. Find a way to make a component H1?.
    // (most simple solution is to keep for each important component H1 in the code, maybe a subclass
    // maincontent? To consider.

    class BasePage extends Component {

        // TODO: Remove?
        private const COMPONENT_EXPR = '/<component \/>/';
        private const COMPONENT_TAG = '<component />';
        private $header;

        // Lists of components added to the BasePage.
        private $components;


        // TODO: Add Components array on construction to give the chance to build from components?
        public function __construct(string $baseLayout) {

            parent::__construct(isset($baseLayout) ? $baseLayout : file_get_contents(__ROOT__.'\view\pages\BaseLayout.xhtml'));
            $this->components = array();
        }

        public function setHeader(Header $head){ $this->header = $head;}

        /* Add here H + value as parameter? */
        public function addComponent(Component $component){

            try {

                $this->components[] = $component; /* Aggiunta di nuovo component */

                $this->notBuilt();
                return true;

            } catch( Exception $e ){ return false; }

        }

        public function build(){

            $HTML = parent::build(); /* Last built is made by template.*/

            foreach ($this->components as $component){

                $componentHTML = $component->returnComponent();

                $HTML = str_replace(self::COMPONENT_TAG, self::COMPONENT_TAG . "\n" . self::COMPONENT_TAG, $HTML);
                $HTML = preg_replace(self::COMPONENT_EXPR, $componentHTML, $HTML, 1);

            }

            return self::cleanTags($HTML);

        }

        private static function cleanTags($HTML){

            /** Cerchiamo i tag non sostituiti e li togliamo. */
            // TODO: Remake
            $HTML = str_replace(self::COMPONENT_TAG, " ", $HTML);
            $HTML = str_replace('<header />',"", $HTML);
            $HTML = str_replace('<sidebar />', "", $HTML);

            return $HTML;

        }

    }

?>