<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "VO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Component.php";

    // Preview diventa generale, la sua struttura dipende dai dati mandati e dal template.
    class Preview extends Component {

        private $data;

        public function __construct(VO $data, string $HTML) {

            parent::__construct($HTML);
            $this->data = $data;

        }


        public function resolveData() {

            $resolvedData = [];

            foreach ($this->data->arrayDump() as $key => $value)
                $resolvedData['{'. $key . '}'] = $value;
            
            return $resolvedData;
        }
    }