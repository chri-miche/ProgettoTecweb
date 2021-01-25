<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";
    class BasePage extends Component {

        private const COMPONENT_EXPR = '/<component \/>/';
        private const COMPONENT_TAG = '<component />';

        private $header;

        // Lists of components added to the BasePage.
        private $components;


        public function __construct(string $baseLayout) {

            parent::__construct($baseLayout ?? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml"));
            $this->components = array();
        }

        public function setHeader(Header $head){ $this->header = $head;}

        /* Add here H + value as parameter? */
        public function addComponent(Component $component){

            $this->components[] = $component;

            $this->notBuilt();
            return true;

        }

        public function build(){

            $HTML = parent::build();

            foreach ($this->components as $component){

                $componentHTML = $component->returnComponent();

                $HTML = str_replace(self::COMPONENT_TAG, self::COMPONENT_TAG . "\n" . self::COMPONENT_TAG, $HTML);
                $HTML = preg_replace(self::COMPONENT_EXPR, $componentHTML, $HTML, 1);

            }

            return self::cleanTags($HTML);

        }

        private static function cleanTags($HTML){

            $HTML = str_replace(self::COMPONENT_TAG, " ", $HTML);
            $HTML = str_replace('<header />',"", $HTML);
            return $HTML;

        }
    }

?>