<?php

    require_once __ROOT__.'\control\components\Component.php';

    //TODO: In page building we have multple possible components this means that
    // it's hard to unserstand the h1, h2 etc stuff. Find a way to make a component H1?.
    // (most simple solution is to keep for each important component H1 in the code, maybe a subclass
    // maincontent? To consider.

    //TODO: Remove globals inside of Post and other Components.


    /* Remove from the side the sidebar? And keep an header that moves with content of page?*/
    // TODO: Check all this out.
    class BasePage {

        /** Array of all components inside of the generic page.*/

        private $components; private $pageHTML;

        private const COMPONENT_EXPR = '/<component \/>/';
        private const COMPONENT_TAG = '<component />';

        private $header;

        private $built;

        private $lastBuiltHTML;

        public function __construct(string $baseLayout) {

            $this->components = array();
            $this->pageHTML = $baseLayout;

            $this->built = false;

        }

        public function setHeader(Header $head){ $this->header = $head;}

        /* Add here H + value as parameter? */
        public function addComponent(Component $component){

            try {

                $this->built = false;
                $this->components[] = $component; /* Aggiunta di nuovo component */

                return true;

            } catch( Exception $e ){ return false; }

        }

        private function build(){

            $this->lastBuiltHTML = $this->pageHTML; /* Last built is made by template.*/

            foreach ($this->components as $component){

                $HTML = $component->build();

                $this->lastBuiltHTML = str_replace(self::COMPONENT_TAG,
            self::COMPONENT_TAG . "\n" . self::COMPONENT_TAG, $this->lastBuiltHTML);


                $this->lastBuiltHTML = preg_replace(self::COMPONENT_EXPR, $HTML, $this->lastBuiltHTML, 1);


            }

            $this->lastBuiltHTML = self::cleanTags($this->lastBuiltHTML);
            return $this->lastBuiltHTML;

        }

        private static function cleanTags($HTML){

            /** Cerchiamo i tag non sostituiti e li togliamo. */
            // TODO: Remake
            $HTML = str_replace(self::COMPONENT_TAG, " ", $HTML);
            $HTML = str_replace('<header />',"", $HTML);
            $HTML = str_replace('<sidebar />', "", $HTML);

            return $HTML;

        }

        public function returnPage(){return ($this->built) ? $this->lastBuiltHTML : $this->build();}
        public function __toString() { return $this->returnPage(); }

    }

?>