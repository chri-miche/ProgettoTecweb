<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "summaries" . DIRECTORY_SEPARATOR . "PageFiller.php";
    /** Creates a banner link with image. (image is optional) */
    class LinkRow extends PageFiller {

        private $reference;

        /** @var VO $genericVO: Implementazione di vo*/
        private $genericVO;

        public function __construct(string $reference, VO $linkTypeVO, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "LinkRow.xhtml"));

            $this->reference = $reference;
            $this->genericVO = $linkTypeVO;

        }


        public function resolveData() {

            $resolvedData['{reference}'] = $this->reference;

            foreach ($this->genericVO->arrayDump() as $key => $value)
                $resolvedData['{'. $key .'}'] = $value;

            return $resolvedData;
        }
    }