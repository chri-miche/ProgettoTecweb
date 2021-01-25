<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "catalogo" . DIRECTORY_SEPARATOR . "Preview.php";

    /** Makes a preview of a given component type and its data. */
    /* In input we get the data (in array of associative array).*/
    class PreviewsPage extends Component {

        private $previews;
        /* Data is already the size it is required to show?*/
        public function __construct(array $data, string $previewLayout, string $HTML = null) {
            /** We don't need the layout from user.*/
            parent::__construct(file_get_contents($HTML ?? __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "browsing" . DIRECTORY_SEPARATOR . "previewPage.xhtml"));

            $this->previews = [];

            foreach ($data as $element)
                $this->previews [] = new Preview($element, $previewLayout);

        }

        public function build(){

            $baseLayout = $this->baseLayout();

            foreach ($this->resolvedData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;

        }

        private function resolvedData(){

            $resolvedData['{previews}'] = '';

            foreach ($this->previews as $preview)
                $resolvedData['{previews}'] .= $preview->returnComponent();
            return $resolvedData;
        }
    }