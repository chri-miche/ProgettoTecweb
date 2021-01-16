<?php
    require_once __ROOT__ . '\control\components\Component.php';
    // Preview diventa generale, la sua struttura dipende dai dati mandati e dal template.
    class Preview extends Component {

        private $data;

        public function __construct(array $data, string $HTML) {

            parent::__construct($HTML);
            $this->data = $data;

        }

        public function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;
        }

        public function resolveData() {

            // TODO: Implement resolveData() method.
            $resolvedData = [];

            foreach ($this->data as $key => $value){
                $resolvedData['{'. $key . '}'] = $value;
            }

            return $resolvedData;
        }
    }