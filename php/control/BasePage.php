<?php

    require_once __ROOT__.'\control\components\Component.php';

    class BasePage {

        /** Array of all components inside of the generic page.*/

        private $components; private $pageHTML;

        private const HEADER_TAG = '/<container \/>/';
        private const COMPONENT_EXPR = '/<component \/>/';
        private const COMPONENT_TAG = '<component />';

        private $lastBuiltHTML;

        public function __construct(string $baseLayout) {

            $this->components = array();

            $this->pageHTML = $baseLayout;

        }

        public function setHeader(Header $head){

            if($head) {

                $this->HTML = str_replace(self::HEADER_TAG, $head, $this->HTML);
                return 'Header successfully set';

            }  else
                /** Make a header destroy and recreate? (reassing)*/
                return 'Header Already set.';

        }


        public function addComponent(Component $component){

            try {

                $this->built = false;
                $this->components[] = $component; /* Aggiunta di nuovo component */

                return true;

            } catch( Exception $e ){

                return false;

            }

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
            $HTML = str_replace(self::COMPONENT_TAG, " ", $HTML);
            $HTML = str_replace('<header />',"", $HTML);

            return $HTML;

        }

        public function returnPage(){

            return ($this->built) ? $this->lastBuiltHTML : $this->build();
        }

         public function __toString() { return $this->returnPage(); }

    }

?>