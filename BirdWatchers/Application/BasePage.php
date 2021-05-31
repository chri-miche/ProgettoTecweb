<?php

    require_once __DIR__ . "/Component.php";
    class BasePage extends Component {

        private const COMPONENT_EXPR = '/<component \/>/';
        private const COMPONENT_TAG = '<component />';

        // Lists of components added to the BasePage.
        private $components;


        public function __construct(string $baseLayout) {
            parent::__construct($baseLayout ?? file_get_contents(__DIR__ . "/BaseLayout.xhtml"));
            $this->components = array();
        }

        public function addComponent(Component $component){
            $this->components[] = $component; $this->notBuilt();
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
            return $HTML;

        }
    }

?>