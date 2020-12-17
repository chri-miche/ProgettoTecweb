<?php


    class BasePage {

        /** Array of all components inside of the generic page.*/
        private $components;
        private $pageHTML;


        public function __construct(string $layout) {

            $this->components = array();

            $this->pageHTML = $layout;
            $this->built = true;

        }


        public function addModule(Component $component){

            $component->setComponent($this->pageHTML);
            $components[] = $component;

            $app = end($components)->getBuild();

            if($app) {

                $this->pageHTML = $app;
                return true;

            }  else {

                array_pop($components);
                return false;

            }
        }

    /** Da pensare meglio sicuramente, così è poco pratico ma funziona al momento.

        Potremmo mettere semplicemente <component /> come tag e fare che è compito della
        costruzione fare tutto in ordine? */ // TODO: Check here
        private static function cleanTags($HTML){

            /** Cerchiamo i tag non sostituiti e li togliamo. */
            $HTML = str_replace('<header />', " ", $HTML);
            $HTML = str_replace('<sideBar />', " ", $HTML);
            $HTML = str_replace('<searchBar />', " ", $HTML);
            $HTML = str_replace('<contents />', " ", $HTML);

            return $HTML;


        }


        public function returnPage(){ return self::cleanTags($this->pageHTML); }
        public function __toString() { return $this->returnPage(); }

    }


?>